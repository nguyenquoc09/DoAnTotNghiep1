<?php
namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Specialty;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home', ['specialties' => Specialty::where('status', 'active')->withCount('doctors')->take(6)->get(), 'doctors' => Doctor::where('status', 'active')->with('user', 'specialty')->take(6)->get()]);
    }
    public function specialties() { return view('public.specialties', ['specialties' => Specialty::where('status', 'active')->withCount('doctors')->paginate(12)]); }
    public function doctors()
    {
        $query = Doctor::where('status', 'active')->with('user', 'specialty');
        if (request('specialty')) { $query->where('specialty_id', request('specialty')); }
        if (request('q')) { $term = request('q'); $query->whereHas('user', function ($q) use ($term) { $q->where('name', 'like', "%{$term}%"); }); }
        return view('public.doctors', ['doctors' => $query->paginate(12)->withQueryString(), 'specialties' => Specialty::where('status', 'active')->get()]);
    }
    public function doctor(Doctor $doctor)
    {
        $doctor->load('user', 'specialty');
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)->where('status', 'active')->whereDate('work_date', '>=', today())->whereDate('work_date', '<=', today()->addDays(14))->orderBy('work_date')->get();
        return view('public.doctor', compact('doctor', 'schedules'));
    }
}
