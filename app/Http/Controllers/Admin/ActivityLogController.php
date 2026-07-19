<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $query = ActivityLog::with('user')->latest('created_at');
        if (request('module')) { $query->where('module', request('module')); }
        if (request('q')) { $query->where('description', 'like', '%' . request('q') . '%'); }
        return view('admin.activity-logs', ['logs' => $query->paginate(20)->withQueryString()]);
    }
}
