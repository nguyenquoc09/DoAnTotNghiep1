<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index() { return view('admin.schedules', ['items' => DoctorSchedule::with('doctor.user')->whereDate('work_date', '>=', today())->orderBy('work_date')->paginate(15), 'doctors' => Doctor::with('user')->where('status', 'active')->get()]); }
    public function store(Request $request)
    {
        $data = $request->validate(['doctor_id' => 'required|exists:doctors,id', 'work_date' => 'required|date|after_or_equal:today', 'shift_name' => 'required|max:50', 'start_time' => 'required|date_format:H:i', 'end_time' => 'required|date_format:H:i|after:start_time', 'maximum_patients' => 'required|integer|min:1|max:100', 'room_number' => 'nullable|max:30', 'status' => 'required|in:active,inactive']);
        $overlap = DoctorSchedule::where('doctor_id', $data['doctor_id'])->whereDate('work_date', $data['work_date'])->where('start_time', '<', $data['end_time'])->where('end_time', '>', $data['start_time'])->exists();
        if ($overlap) { return back()->withErrors(['start_time' => 'Lịch làm việc bị chồng chéo.'])->withInput(); }
        DoctorSchedule::create($data); return back()->with('success', 'Đã tạo lịch làm việc.');
    }
    public function destroy(DoctorSchedule $schedule) { if ($schedule->appointments()->exists()) { $schedule->update(['status' => 'inactive']); } else { $schedule->delete(); } return back()->with('success', 'Đã ngừng lịch làm việc.'); }
}
