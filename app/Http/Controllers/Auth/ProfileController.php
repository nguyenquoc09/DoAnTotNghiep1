<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit() { return view('auth.profile', ['user' => auth()->user()]); }
    public function update(Request $request)
    {
        $data = $request->validate(['name' => 'required|max:150', 'phone' => ['nullable', 'regex:/^(0|\+84)[0-9]{9}$/'], 'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', 'date_of_birth' => 'nullable|date|before_or_equal:today', 'gender' => 'nullable|in:male,female,other', 'address' => 'nullable|string|max:255']);
        if ($request->hasFile('avatar')) {
            $name = 'avatar_' . $request->user()->id . '_' . time() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path('uploads/avatars'), $name);
            $data['avatar'] = 'uploads/avatars/' . $name;
        }
        $request->user()->update($data);
        if ($request->user()->patient) { $request->user()->patient->update(['full_name' => $data['name'], 'phone' => $data['phone'], 'date_of_birth' => $data['date_of_birth'], 'gender' => $data['gender'], 'address' => $data['address']]); }
        return back()->with('success', 'Đã cập nhật hồ sơ.');
    }
    public function password(Request $request)
    {
        $data = $request->validate(['current_password' => 'required', 'password' => 'required|min:8|confirmed']);
        if (!Hash::check($data['current_password'], $request->user()->password)) { return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']); }
        $request->user()->update(['password' => Hash::make($data['password'])]);
        return back()->with('success', 'Đổi mật khẩu thành công.');
    }
}
