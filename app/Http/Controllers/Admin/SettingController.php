<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit() { return view('admin.settings', ['setting' => ClinicSetting::firstOrCreate([])]); }
    public function update(Request $request) { $data = $request->validate(['clinic_name' => 'required|max:255', 'phone' => 'nullable|max:20', 'email' => 'nullable|email', 'address' => 'nullable|max:255', 'opening_time' => 'required', 'closing_time' => 'required|after:opening_time', 'examination_policy' => 'nullable|string', 'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048']); if ($request->hasFile('logo')) { $name = 'clinic_' . time() . '.' . $request->file('logo')->getClientOriginalExtension(); $request->file('logo')->move(public_path('uploads/clinic'), $name); $data['logo'] = 'uploads/clinic/' . $name; } ClinicSetting::firstOrCreate([])->update($data); return back()->with('success', 'Đã cập nhật thông tin phòng khám.'); }
}
