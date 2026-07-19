<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['email' => 'required|email', 'password' => 'required|string', 'remember' => 'nullable|boolean']; }
    public function messages() { return ['email.required' => 'Vui lòng nhập email.', 'email.email' => 'Email không đúng định dạng.', 'password.required' => 'Vui lòng nhập mật khẩu.']; }
}
