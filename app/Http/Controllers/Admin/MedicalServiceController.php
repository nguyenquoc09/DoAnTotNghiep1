<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalService;
use App\Models\Specialty;
use Illuminate\Http\Request;

class MedicalServiceController extends Controller
{
    public function index() { return view('admin.services', ['items' => MedicalService::with('specialty')->paginate(15), 'specialties' => Specialty::where('status', 'active')->get()]); }
    public function store(Request $request) { MedicalService::create($this->data($request)); return back()->with('success', 'Đã thêm dịch vụ.'); }
    public function update(Request $request, MedicalService $medicalService) { $medicalService->update($this->data($request, $medicalService->id)); return back()->with('success', 'Đã cập nhật dịch vụ.'); }
    private function data(Request $request, $id = null) { return $request->validate(['specialty_id' => 'nullable|exists:specialties,id', 'service_code' => 'required|max:20|unique:medical_services,service_code,' . ($id ?: 'NULL'), 'name' => 'required|max:150', 'description' => 'nullable|string', 'price' => 'required|numeric|min:0', 'status' => 'required|in:active,inactive']); }
}
