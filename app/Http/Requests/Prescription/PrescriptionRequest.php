<?php
namespace App\Http\Requests\Prescription;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['general_instruction' => 'nullable|string', 'items' => 'nullable|array', 'items.*.medicine_id' => 'required|distinct|exists:medicines,id', 'items.*.quantity' => 'required|integer|min:1', 'items.*.dosage' => 'required|string|max:100', 'items.*.frequency' => 'nullable|string|max:100', 'items.*.duration' => 'nullable|string|max:100', 'items.*.usage_time' => 'nullable|string|max:100', 'items.*.instruction' => 'nullable|string|max:500']; }
}
