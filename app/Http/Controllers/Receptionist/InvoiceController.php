<?php
namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\PaymentRequest;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index() { $query = Invoice::with('patient'); if (request('status')) { $query->where('payment_status', request('status')); } if (request('q')) { $term = request('q'); $query->where('invoice_code', 'like', "%{$term}%")->orWhereHas('patient', function ($p) use ($term) { $p->where('full_name', 'like', "%{$term}%"); }); } return view('receptionist.invoices', ['invoices' => $query->latest()->paginate(15)->withQueryString(), 'records' => MedicalRecord::where('status', 'completed')->doesntHave('invoice')->with('patient')->get()]); }
    public function store(Request $request, InvoiceService $service) { $data = $request->validate(['medical_record_id' => 'required|exists:medical_records,id', 'discount_amount' => 'nullable|numeric|min:0']); $service->create(MedicalRecord::findOrFail($data['medical_record_id']), $request->user(), $data['discount_amount'] ?: 0); return back()->with('success', 'Đã lập hóa đơn và cập nhật tồn kho.'); }
    public function show(Invoice $invoice) { return view('receptionist.invoice', ['invoice' => $invoice->load('patient', 'items', 'payments.receiver')]); }
    public function pay(PaymentRequest $request, Invoice $invoice, InvoiceService $service) { $service->pay($invoice, $request->validated(), $request->user()); return back()->with('success', 'Đã ghi nhận thanh toán.'); }
    public function cancel(Invoice $invoice, InvoiceService $service) { $service->cancel($invoice, request()->user()); return redirect()->route('receptionist.invoices.index')->with('success', 'Đã hủy hóa đơn và hoàn tồn kho an toàn.'); }
    public function print(Invoice $invoice) { return view('receptionist.invoice-print', ['invoice' => $invoice->load('patient', 'items', 'payments')]); }
}
