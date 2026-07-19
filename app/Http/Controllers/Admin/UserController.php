<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $query = User::with('role');
        if (request('q')) { $term = request('q'); $query->where(function ($q) use ($term) { $q->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%")->orWhere('phone', 'like', "%{$term}%"); }); }
        if (request('role')) { $query->whereHas('role', function ($q) { $q->where('code', request('role')); }); }
        return view('admin.users', ['users' => $query->latest()->paginate(15)->withQueryString(), 'roles' => Role::all()]);
    }
    public function update(Request $request, User $user)
    {
        $data = $request->validate(['role_id' => 'required|exists:roles,id', 'status' => 'required|in:active,locked']);
        if ($user->id === $request->user()->id && $data['status'] === User::STATUS_LOCKED) { return back()->withErrors(['status' => 'Không thể tự khóa tài khoản đang đăng nhập.']); }
        $user->update($data); return back()->with('success', 'Đã cập nhật tài khoản.');
    }
    public function resetPassword(User $user) { $user->update(['password' => Hash::make('Password123!')]); return back()->with('success', 'Đã đặt lại mật khẩu thành Password123!'); }
}
