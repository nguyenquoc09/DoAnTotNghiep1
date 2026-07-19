<?php
namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        $id = $this->route('patient') ? $this->route('patient')->id : 'NULL';
        return ['full_name' => 'required|string|max:150', 'date_of_birth' => 'nullable|date|before_or_equal:today', 'gender' => 'nullable|in:male,female,other', 'phone' => ['required', 'regex:/^(0|\+84)[0-9]{9}$/'], 'email' => 'nullable|email', 'identity_number' => 'nullable|max:30|unique:patients,identity_number,' . $id, 'address' => 'nullable|max:255', 'status' => 'nullable|in:active,inactive'];
    }
}
