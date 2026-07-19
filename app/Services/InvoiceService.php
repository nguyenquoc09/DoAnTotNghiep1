<?php
namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    private $logs;

    public function __construct(ActivityLogService $logs) { $this->logs = $logs; }

    public function create(MedicalRecord $record, User $actor, $discount = 0)
    {
        return DB::transaction(function () use ($record, $actor, $discount) {
            $record->load('ticket.appointment.medicalService', 'prescription.items.medicine');
            if ($record->status !== MedicalRecord::STATUS_COMPLETED) { throw ValidationException::withMessages(['record' => 'Phiên khám chưa hoàn thành.']); }
            if (Invoice::where('medical_record_id', $record->id)->exists()) { throw ValidationException::withMessages(['record' => 'Phiên khám đã có hóa đơn.']); }
            $service = $record->ticket->appointment->medicalService;
            $serviceAmount = $service ? (float) $service->price : (float) $record->doctor->consultation_fee;
            $medicineAmount = $record->prescription ? (float) $record->prescription->items->sum('total_price') : 0;
            $subtotal = $serviceAmount + $medicineAmount;
            $discount = max(0, min((float) $discount, $subtotal));
            $invoice = Invoice::create([
                'patient_id' => $record->patient_id, 'appointment_id' => $record->ticket->appointment_id,
                'examination_ticket_id' => $record->examination_ticket_id, 'medical_record_id' => $record->id,
                'service_amount' => $serviceAmount, 'medicine_amount' => $medicineAmount, 'discount_amount' => $discount,
                'total_amount' => $subtotal - $discount, 'paid_amount' => 0, 'payment_status' => Invoice::STATUS_UNPAID,
                'created_by' => $actor->id,
            ]);
            if ($service) { $invoice->items()->create(['item_type' => InvoiceItem::TYPE_SERVICE, 'reference_id' => $service->id, 'description' => $service->name, 'quantity' => 1, 'unit_price' => $serviceAmount, 'amount' => $serviceAmount]); }
            if ($record->prescription) {
                foreach ($record->prescription->items as $item) {
                    $medicine = Medicine::whereKey($item->medicine_id)->lockForUpdate()->firstOrFail();
                    if ($medicine->stock_quantity < $item->quantity) { throw ValidationException::withMessages(['stock' => "Thuốc {$medicine->name} không đủ tồn kho."]); }
                    $medicine->decrement('stock_quantity', $item->quantity);
                    $invoice->items()->create(['item_type' => InvoiceItem::TYPE_MEDICINE, 'reference_id' => $medicine->id, 'description' => $medicine->name, 'quantity' => $item->quantity, 'unit_price' => $item->unit_price, 'amount' => $item->total_price]);
                }
                $invoice->update(['stock_deducted_at' => now()]);
            }
            $this->logs->write('create', 'invoice', 'Lập hóa đơn ' . $invoice->invoice_code, $actor);
            return $invoice->load('items');
        });
    }

    public function pay(Invoice $invoice, array $data, User $actor)
    {
        return DB::transaction(function () use ($invoice, $data, $actor) {
            $locked = Invoice::whereKey($invoice->id)->lockForUpdate()->firstOrFail();
            if (in_array($locked->payment_status, [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED], true) || (float) $data['amount'] > $locked->outstanding_amount) {
                throw ValidationException::withMessages(['amount' => 'Số tiền thanh toán không hợp lệ.']);
            }
            Payment::create(['invoice_id' => $locked->id, 'amount' => $data['amount'], 'payment_method' => $data['payment_method'], 'transaction_reference' => isset($data['transaction_reference']) ? $data['transaction_reference'] : null, 'payment_date' => now(), 'received_by' => $actor->id, 'status' => Payment::STATUS_COMPLETED, 'note' => isset($data['note']) ? $data['note'] : null]);
            $paid = (float) $locked->payments()->where('status', Payment::STATUS_COMPLETED)->sum('amount');
            $status = $paid >= (float) $locked->total_amount ? Invoice::STATUS_PAID : Invoice::STATUS_PARTIALLY_PAID;
            $locked->update(['paid_amount' => $paid, 'payment_status' => $status, 'paid_at' => $status === Invoice::STATUS_PAID ? now() : null]);
            $this->logs->write('payment', 'invoice', 'Ghi nhận thanh toán hóa đơn ' . $locked->invoice_code, $actor);
            return $locked->fresh('payments');
        });
    }

    public function cancel(Invoice $invoice, User $actor)
    {
        return DB::transaction(function () use ($invoice, $actor) {
            $locked = Invoice::whereKey($invoice->id)->lockForUpdate()->firstOrFail();
            if ($locked->payment_status !== Invoice::STATUS_UNPAID) {
                throw ValidationException::withMessages(['invoice' => 'Chỉ hóa đơn chưa thanh toán mới có thể hủy.']);
            }
            if ($locked->stock_deducted_at && !$locked->stock_restored_at) {
                foreach ($locked->items()->where('item_type', InvoiceItem::TYPE_MEDICINE)->get() as $item) {
                    Medicine::whereKey($item->reference_id)->lockForUpdate()->firstOrFail()->increment('stock_quantity', $item->quantity);
                }
            }
            $locked->update(['payment_status' => Invoice::STATUS_CANCELLED, 'stock_restored_at' => $locked->stock_deducted_at ? now() : null]);
            $this->logs->write('cancel', 'invoice', 'Hủy hóa đơn ' . $locked->invoice_code . ' và hoàn tồn kho', $actor);
            return $locked;
        });
    }
}
