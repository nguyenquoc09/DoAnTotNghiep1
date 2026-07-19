<?php
namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\MedicalService;
use App\Models\Patient;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $query = Appointment::with('patient', 'doctor.user', 'specialty');
        if (request('status')) { $query->where('status', request('status')); }
        if (request('date')) { $query->whereDate('appointment_date', request('date')); }
        if (request('q')) { $term = request('q'); $query->where(function ($q) use ($term) { $q->where('appointment_code', 'like', "%{$term}%")->orWhereHas('patient', function ($p) use ($term) { $p->where('full_name', 'like', "%{$term}%"); }); }); }
        $schedules = DoctorSchedule::with('doctor.user', 'appointments')->whereDate('work_date', '>=', today())->where('status', 'active')->orderBy('work_date')->get();
        $schedules->each(function ($schedule) { $schedule->available_slots = $schedule->availableSlots(); });
        return view('receptionist.appointments', ['appointments' => $query->orderByDesc('appointment_date')->paginate(15)->withQueryString(), 'patients' => Patient::where('status', 'active')->get(), 'doctors' => Doctor::with('user')->where('status', 'active')->get(), 'schedules' => $schedules, 'services' => MedicalService::where('status', 'active')->get()]);
    }
    public function store(StoreAppointmentRequest $request, AppointmentService $service) { $data = array_merge($request->validated(), ['booking_source' => Appointment::SOURCE_RECEPTIONIST]); $service->book($data, $request->user()); return back()->with('success', 'Đã đặt lịch và xác nhận tại quầy.'); }
    public function confirm(Appointment $appointment, AppointmentService $service) { $service->confirm($appointment, request()->user()); return back()->with('success', 'Đã xác nhận lịch hẹn.'); }
    public function cancel(Request $request, Appointment $appointment, AppointmentService $service) { $request->validate(['reason' => 'required|max:1000']); $service->cancel($appointment, $request->reason); return back()->with('success', 'Đã hủy lịch hẹn.'); }
    public function checkIn(Appointment $appointment, AppointmentService $service) { $ticket = $service->checkIn($appointment, request()->user()); return back()->with('success', 'Tiếp nhận thành công. Số thứ tự: ' . $ticket->queue_number); }
}
