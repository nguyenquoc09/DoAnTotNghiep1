<?php
namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'medical_service_id' => 'nullable|exists:medical_services,id',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:1000',
            'symptoms_note' => 'nullable|string|max:2000',
        ];
    }
    public function messages() { return ['reason.required' => 'Vui lòng cho biết lý do khám.', 'appointment_time.required' => 'Vui lòng chọn khung giờ khám.']; }
}
