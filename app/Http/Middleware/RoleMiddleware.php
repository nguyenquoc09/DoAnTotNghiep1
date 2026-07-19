<?php
namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!$request->user() || !$request->user()->isActive() || !$request->user()->role || !in_array($request->user()->role->code, $roles, true)) {
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }
        return $next($request);
    }
}
