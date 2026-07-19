<?php
namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function dashboard(array $filters = [])
    {
        $appointments = Appointment::query();
        if (!empty($filters['from'])) { $appointments->whereDate('appointment_date', '>=', $filters['from']); }
        if (!empty($filters['to'])) { $appointments->whereDate('appointment_date', '<=', $filters['to']); }
        if (!empty($filters['specialty_id'])) { $appointments->where('specialty_id', $filters['specialty_id']); }
        if (!empty($filters['doctor_id'])) { $appointments->where('doctor_id', $filters['doctor_id']); }

        $paidInvoices = Invoice::where('payment_status', Invoice::STATUS_PAID);
        if (!empty($filters['from'])) { $paidInvoices->whereDate('paid_at', '>=', $filters['from']); }
        if (!empty($filters['to'])) { $paidInvoices->whereDate('paid_at', '<=', $filters['to']); }
        if (!empty($filters['doctor_id']) || !empty($filters['specialty_id'])) {
            $paidInvoices->whereHas('appointment', function ($query) use ($filters) {
                if (!empty($filters['doctor_id'])) { $query->where('doctor_id', $filters['doctor_id']); }
                if (!empty($filters['specialty_id'])) { $query->where('specialty_id', $filters['specialty_id']); }
            });
        }

        return [
            'patients' => Patient::count(), 'doctors' => Doctor::count(), 'appointments' => (clone $appointments)->count(),
            'todayAppointments' => Appointment::whereDate('appointment_date', today())->count(),
            'completed' => (clone $appointments)->where('status', Appointment::STATUS_COMPLETED)->count(),
            'todayRevenue' => Invoice::where('payment_status', Invoice::STATUS_PAID)->whereDate('paid_at', today())->sum('total_amount'),
            'monthRevenue' => Invoice::where('payment_status', Invoice::STATUS_PAID)->whereYear('paid_at', now()->year)->whereMonth('paid_at', now()->month)->sum('total_amount'),
            'filteredRevenue' => (clone $paidInvoices)->sum('total_amount'),
            'unpaidInvoices' => Invoice::whereIn('payment_status', [Invoice::STATUS_UNPAID, Invoice::STATUS_PARTIALLY_PAID])->count(),
            'statusChart' => (clone $appointments)->selectRaw('status, COUNT(*) total')->groupBy('status')->pluck('total', 'status'),
            'revenueByMonth' => Invoice::where('payment_status', Invoice::STATUS_PAID)->whereDate('paid_at', '>=', today()->subMonths(11)->startOfMonth())->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') month, SUM(total_amount) total")->groupBy('month')->orderBy('month')->pluck('total', 'month'),
            'visitsBySpecialty' => Appointment::join('specialties', 'specialties.id', '=', 'appointments.specialty_id')->where('appointments.status', Appointment::STATUS_COMPLETED)->selectRaw('specialties.name, COUNT(*) total')->groupBy('specialties.id', 'specialties.name')->orderByDesc('total')->pluck('total', 'name'),
            'topDoctors' => Doctor::with('user')->withCount(['appointments as completed_count' => function ($query) { $query->where('status', Appointment::STATUS_COMPLETED); }])->orderByDesc('completed_count')->take(5)->get(),
            'topMedicines' => Medicine::join('prescription_items', 'medicines.id', '=', 'prescription_items.medicine_id')->select('medicines.name', DB::raw('SUM(prescription_items.quantity) total'))->groupBy('medicines.id', 'medicines.name')->orderByDesc('total')->take(5)->get(),
        ];
    }
}
