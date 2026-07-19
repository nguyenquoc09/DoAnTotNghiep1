<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpecialtyController extends Controller
{
    public function index() { return view('admin.specialties', ['items' => Specialty::withCount('doctors')->latest()->paginate(15)]); }
    public function store(Request $request) { $data = $request->validate(['name' => 'required|max:150|unique:specialties,name', 'description' => 'nullable|string', 'status' => 'required|in:active,inactive']); $data['slug'] = Str::slug($data['name']); Specialty::create($data); return back()->with('success', 'Đã thêm chuyên khoa.'); }
    public function update(Request $request, Specialty $specialty) { $data = $request->validate(['name' => 'required|max:150|unique:specialties,name,' . $specialty->id, 'description' => 'nullable|string', 'status' => 'required|in:active,inactive']); $data['slug'] = Str::slug($data['name']); $specialty->update($data); return back()->with('success', 'Đã cập nhật chuyên khoa.'); }
    public function destroy(Specialty $specialty) { if ($specialty->doctors()->exists()) { return back()->withErrors(['delete' => 'Không thể xóa chuyên khoa đã có bác sĩ.']); } $specialty->delete(); return back()->with('success', 'Đã xóa chuyên khoa.'); }
}
