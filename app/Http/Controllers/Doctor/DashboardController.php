<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\ExaminationTicket;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $doctor = auth()->user()->doctor;
        $tickets = ExaminationTicket::with('patient')->where('doctor_id', $doctor->id)->whereDate('examination_date', today())->orderBy('queue_number')->get();
        return view('doctor.dashboard', ['doctor' => $doctor->load('schedules'), 'tickets' => $tickets]);
    }
}
