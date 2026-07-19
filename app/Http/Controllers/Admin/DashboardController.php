<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;

class DashboardController extends Controller
{
    public function __invoke(ReportService $reports) { return view('admin.dashboard', $reports->dashboard()); }
}
