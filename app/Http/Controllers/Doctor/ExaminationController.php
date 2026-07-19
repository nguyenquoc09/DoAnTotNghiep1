<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Examination\MedicalRecordRequest;
use App\Http\Requests\Prescription\PrescriptionRequest;
use App\Models\ExaminationTicket;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Services\ExaminationService;
use App\Services\PrescriptionService;

class ExaminationController extends Controller
{
    private function guardTicket(ExaminationTicket $ticket) { if (!auth()->user()->doctor || $ticket->doctor_id !== auth()->user()->doctor->id) { abort(403); } }
    private function guardRecord(MedicalRecord $record) { if (!auth()->user()->doctor || $record->doctor_id !== auth()->user()->doctor->id) { abort(403); } }
    public function show(ExaminationTicket $ticket) { $this->guardTicket($ticket); $ticket->load('patient.medicalRecords.doctor.user', 'medicalRecord.prescription.items.medicine'); return view('doctor.examination', ['ticket' => $ticket, 'medicines' => Medicine::where('status', 'active')->where('stock_quantity', '>', 0)->get()]); }
    public function start(ExaminationTicket $ticket, ExaminationService $service) { $this->guardTicket($ticket); $service->start($ticket, request()->user()); return redirect()->route('doctor.examinations.show', $ticket)->with('success', 'Đã bắt đầu phiên khám.'); }
    public function update(MedicalRecordRequest $request, MedicalRecord $record, ExaminationService $service) { $this->guardRecord($record); $service->save($record, $request->validated()); return back()->with('success', 'Đã lưu hồ sơ khám.'); }
    public function prescription(PrescriptionRequest $request, MedicalRecord $record, PrescriptionService $service) { $this->guardRecord($record); $service->save($record, $request->validated()); return back()->with('success', 'Đã lưu đơn thuốc.'); }
    public function complete(MedicalRecordRequest $request, MedicalRecord $record, ExaminationService $service) { $this->guardRecord($record); $service->complete($record, $request->validated()); return redirect()->route('doctor.dashboard')->with('success', 'Đã hoàn thành phiên khám.'); }
}
