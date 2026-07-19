<?php
namespace App\Http\Requests\Examination;

use Illuminate\Foundation\Http\FormRequest;

class MedicalRecordRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['chief_complaint' => 'nullable|string', 'current_symptoms' => 'nullable|string', 'medical_history' => 'nullable|string', 'family_history' => 'nullable|string', 'pulse' => 'nullable|integer|between:20,250', 'temperature' => 'nullable|numeric|between:30,45', 'blood_pressure' => 'nullable|string|max:20', 'weight' => 'nullable|numeric|between:1,500', 'height' => 'nullable|numeric|between:30,250', 'diagnosis' => 'nullable|string', 'conclusion' => 'nullable|string', 'treatment_plan' => 'nullable|string', 'doctor_note' => 'nullable|string', 'follow_up_date' => 'nullable|date|after_or_equal:today']; }
}
