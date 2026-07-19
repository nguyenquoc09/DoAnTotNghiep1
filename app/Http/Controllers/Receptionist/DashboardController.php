<?php
namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ExaminationTicket;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('receptionist.dashboard', ['todayAppointments' => Appointment::whereDate('appointment_date', today())->count(), 'waiting' => ExaminationTicket::where('status', 'waiting')->count(), 'inProgress' => ExaminationTicket::where('status', 'in_progress')->count(), 'unpaid' => Invoice::whereIn('payment_status', ['unpaid', 'partially_paid'])->count(), 'appointments' => Appointment::with('patient', 'doctor.user')->whereDate('appointment_date', today())->orderBy('appointment_time')->take(10)->get()]);
    }
}
