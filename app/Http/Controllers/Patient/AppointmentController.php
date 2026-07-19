<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\MedicalService;
use App\Models\Specialty;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index() { return view('patient.appointments', ['appointments' => auth()->user()->patient->appointments()->with('doctor.user', 'specialty')->latest('appointment_date')->paginate(10)]); }
    public function create() { $schedules = DoctorSchedule::with('doctor.user', 'appointments')->where('status', 'active')->whereDate('work_date', '>=', today())->whereDate('work_date', '<=', today()->addDays(14))->orderBy('work_date')->get(); $schedules->each(function ($schedule) { $schedule->available_slots = $schedule->availableSlots(); }); return view('patient.book', ['specialties' => Specialty::where('status', 'active')->get(), 'doctors' => Doctor::with('user', 'specialty')->where('status', 'active')->get(), 'schedules' => $schedules, 'services' => MedicalService::where('status', 'active')->get()]); }
    public function store(StoreAppointmentRequest $request, AppointmentService $service) { $data = array_merge($request->validated(), ['patient_id' => $request->user()->patient->id, 'booking_source' => Appointment::SOURCE_ONLINE]); $service->book($data, $request->user()); return redirect()->route('patient.appointments.index')->with('success', 'Đặt lịch thành công. Phòng khám sẽ sớm xác nhận.'); }
    public function cancel(Request $request, Appointment $appointment, AppointmentService $service) { if ($appointment->patient_id !== $request->user()->patient->id) { abort(403); } $request->validate(['reason' => 'required|max:1000']); $service->cancel($appointment, $request->reason); return back()->with('success', 'Đã hủy lịch hẹn.'); }
}
