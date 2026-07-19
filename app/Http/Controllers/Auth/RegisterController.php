<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show() { return view('auth.register'); }
    public function register(RegisterRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            $role = Role::where('code', Role::PATIENT)->firstOrFail();
            $user = User::create(['role_id' => $role->id, 'name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'password' => Hash::make($request->password), 'status' => User::STATUS_ACTIVE]);
            Patient::create(['user_id' => $user->id, 'full_name' => $request->name, 'date_of_birth' => $request->date_of_birth, 'gender' => $request->gender, 'phone' => $request->phone, 'email' => $request->email, 'address' => $request->address, 'status' => Patient::STATUS_ACTIVE]);
            return $user;
        });
        Auth::login($user);
        return redirect()->route('patient.dashboard')->with('success', 'Chào mừng bạn đến với Phòng khám An Tâm!');
    }
}
