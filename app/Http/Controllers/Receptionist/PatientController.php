<?php
namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\PatientRequest;
use App\Models\Patient;

class PatientController extends Controller
{
    public function index() { $query = Patient::query(); if (request('q')) { $query->search(request('q')); } if (request('gender')) { $query->where('gender', request('gender')); } return view('receptionist.patients', ['patients' => $query->latest()->paginate(15)->withQueryString()]); }
    public function store(PatientRequest $request) { Patient::create(array_merge($request->validated(), ['created_by' => $request->user()->id, 'status' => $request->status ?: Patient::STATUS_ACTIVE])); return back()->with('success', 'Đã tạo hồ sơ bệnh nhân.'); }
    public function update(PatientRequest $request, Patient $patient) { $patient->update($request->validated()); return back()->with('success', 'Đã cập nhật bệnh nhân.'); }
    public function show(Patient $patient) { $patient->load(['appointments.doctor.user', 'medicalRecords.doctor.user', 'invoices']); return view('receptionist.patient', compact('patient')); }
}
