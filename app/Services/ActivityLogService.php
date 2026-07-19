<?php
namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogService
{
    public function write($action, $module, $description, $user = null, Request $request = null)
    {
        return ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? substr((string) $request->userAgent(), 0, 1000) : null,
            'created_at' => now(),
        ]);
    }
}
