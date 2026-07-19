<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $query = Doctor::with('user', 'specialty');
        if (request('specialty')) { $query->where('specialty_id', request('specialty')); }
        if (request('q')) { $term = request('q'); $query->where(function ($q) use ($term) { $q->where('doctor_code', 'like', "%{$term}%")->orWhereHas('user', function ($u) use ($term) { $u->where('name', 'like', "%{$term}%"); }); }); }
        return view('admin.doctors', ['doctors' => $query->paginate(15)->withQueryString(), 'specialties' => Specialty::where('status', 'active')->get()]);
    }
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        DB::transaction(function () use ($data) { $role = Role::where('code', Role::DOCTOR)->firstOrFail(); $user = User::create(['role_id' => $role->id, 'name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'], 'password' => Hash::make($data['password']), 'status' => 'active']); Doctor::create(array_merge($data, ['user_id' => $user->id])); });
        return back()->with('success', 'Đã tạo hồ sơ bác sĩ.');
    }
    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate(['name' => 'required|max:150', 'phone' => 'nullable|max:20', 'specialty_id' => 'required|exists:specialties,id', 'degree' => 'nullable|max:100', 'academic_title' => 'nullable|max:100', 'years_of_experience' => 'required|integer|min:0|max:70', 'consultation_fee' => 'required|numeric|min:0', 'room_number' => 'nullable|max:30', 'biography' => 'nullable|string', 'status' => 'required|in:active,inactive']);
        DB::transaction(function () use ($doctor, $data) { $doctor->user->update(['name' => $data['name'], 'phone' => $data['phone']]); $doctor->update($data); }); return back()->with('success', 'Đã cập nhật bác sĩ.');
    }
    private function validateData(Request $request) { return $request->validate(['name' => 'required|max:150', 'email' => 'required|email|unique:users,email', 'phone' => 'nullable|max:20', 'password' => 'required|min:8', 'specialty_id' => 'required|exists:specialties,id', 'degree' => 'nullable|max:100', 'academic_title' => 'nullable|max:100', 'years_of_experience' => 'required|integer|min:0|max:70', 'consultation_fee' => 'required|numeric|min:0', 'room_number' => 'nullable|max:30', 'biography' => 'nullable|string', 'status' => 'required|in:active,inactive']); }
}
