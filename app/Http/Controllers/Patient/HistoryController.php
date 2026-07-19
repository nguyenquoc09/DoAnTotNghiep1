<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use App\Models\Prescription;

class HistoryController extends Controller
{
    private function owns($model) { if ($model->patient_id !== auth()->user()->patient->id) { abort(403); } }
    public function index() { return view('patient.history', ['records' => auth()->user()->patient->medicalRecords()->with('doctor.user', 'prescription')->latest()->paginate(10)]); }
    public function record(MedicalRecord $record) { $this->owns($record); return view('patient.record', ['record' => $record->load('doctor.user', 'prescription.items.medicine')]); }
    public function prescription(Prescription $prescription) { $this->owns($prescription); return view('patient.prescription', ['prescription' => $prescription->load('doctor.user', 'items.medicine')]); }
    public function invoices() { return view('patient.invoices', ['invoices' => auth()->user()->patient->invoices()->latest()->paginate(10)]); }
    public function invoice(Invoice $invoice) { $this->owns($invoice); return view('patient.invoice', ['invoice' => $invoice->load('items', 'payments')]); }
}
