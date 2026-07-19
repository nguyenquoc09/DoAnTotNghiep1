<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        return ['name' => 'required|string|max:150', 'email' => 'required|email|max:255|unique:users,email', 'phone' => ['required', 'regex:/^(0|\+84)[0-9]{9}$/'], 'date_of_birth' => 'nullable|date|before_or_equal:today', 'gender' => 'nullable|in:male,female,other', 'address' => 'nullable|string|max:255', 'password' => 'required|string|min:8|confirmed'];
    }
    public function messages() { return ['name.required' => 'Vui lòng nhập họ tên.', 'email.unique' => 'Email đã được sử dụng.', 'phone.regex' => 'Số điện thoại không đúng định dạng Việt Nam.', 'password.confirmed' => 'Xác nhận mật khẩu chưa khớp.']; }
}
