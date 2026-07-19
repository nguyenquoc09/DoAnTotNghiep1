<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Models\Doctor;
use App\Models\Specialty;

class ReportController extends Controller
{
    public function index(ReportService $reports) { return view('admin.reports', array_merge($reports->dashboard(request()->all()), ['doctorOptions' => Doctor::with('user')->get(), 'specialtyOptions' => Specialty::where('status', 'active')->get()])); }
}
