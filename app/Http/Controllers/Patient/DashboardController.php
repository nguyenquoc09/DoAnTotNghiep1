<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $patient = auth()->user()->patient;
        return view('patient.dashboard', ['patient' => $patient, 'upcoming' => $patient->appointments()->with('doctor.user', 'specialty')->whereDate('appointment_date', '>=', today())->whereNotIn('status', ['cancelled', 'completed'])->orderBy('appointment_date')->take(5)->get(), 'recentRecords' => $patient->medicalRecords()->with('doctor.user')->latest()->take(5)->get(), 'unpaid' => $patient->invoices()->whereIn('payment_status', ['unpaid', 'partially_paid'])->get()]);
    }
}
