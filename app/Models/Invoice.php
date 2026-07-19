<?php
namespace App\Models;

use App\Support\GeneratesCode;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use GeneratesCode;
    const STATUS_UNPAID = 'unpaid'; const STATUS_PARTIALLY_PAID = 'partially_paid'; const STATUS_PAID = 'paid'; const STATUS_CANCELLED = 'cancelled';
    protected $fillable = ['invoice_code', 'patient_id', 'appointment_id', 'examination_ticket_id', 'medical_record_id', 'service_amount', 'medicine_amount', 'discount_amount', 'total_amount', 'paid_amount', 'payment_status', 'created_by', 'paid_at', 'stock_deducted_at', 'stock_restored_at', 'note'];
    protected $casts = ['paid_at' => 'datetime', 'stock_deducted_at' => 'datetime', 'stock_restored_at' => 'datetime'];
    protected function getCodeColumn() { return 'invoice_code'; }
    protected function getCodePrefix() { return 'HD'; }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function ticket() { return $this->belongsTo(ExaminationTicket::class, 'examination_ticket_id'); }
    public function medicalRecord() { return $this->belongsTo(MedicalRecord::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function getOutstandingAmountAttribute() { return max(0, (float) $this->total_amount - (float) $this->paid_amount); }
}
