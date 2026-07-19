<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show() { return view('auth.login'); }
    public function login(LoginRequest $request, ActivityLogService $logs)
    {
        $credentials = $request->only('email', 'password');
        $user = User::with('role')->where('email', $credentials['email'])->first();
        if (!$user || !$user->isActive() || !Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => $user && !$user->isActive() ? 'Tài khoản đã bị khóa.' : 'Email hoặc mật khẩu không chính xác.'])->withInput($request->only('email'));
        }
        $request->session()->regenerate();
        $user->update(['last_login_at' => now()]);
        $logs->write('login', 'auth', 'Đăng nhập hệ thống', $user, $request);
        return redirect()->intended(route($user->dashboardRoute()));
    }
    public function logout(ActivityLogService $logs)
    {
        $user = auth()->user();
        $logs->write('logout', 'auth', 'Đăng xuất hệ thống', $user, request());
        Auth::logout(); request()->session()->invalidate(); request()->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Bạn đã đăng xuất an toàn.');
    }
}
