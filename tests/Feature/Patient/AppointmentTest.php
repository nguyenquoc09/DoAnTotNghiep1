<?php
namespace Tests\Feature\Patient;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    private function fixtures()
    {
        $doctorRole = Role::create(['name' => 'Bác sĩ', 'code' => Role::DOCTOR]);
        $patientRole = Role::create(['name' => 'Bệnh nhân', 'code' => Role::PATIENT]);
        $doctorUser = User::create(['role_id' => $doctorRole->id, 'name' => 'Bác sĩ An', 'email' => 'doctor@test.local', 'password' => bcrypt('password'), 'status' => 'active']);
        $patientUser = User::create(['role_id' => $patientRole->id, 'name' => 'Người bệnh', 'email' => 'patient@test.local', 'password' => bcrypt('password'), 'status' => 'active']);
        $specialty = Specialty::create(['name' => 'Nội khoa', 'slug' => 'noi-khoa', 'status' => 'active']);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialty_id' => $specialty->id, 'consultation_fee' => 300000, 'status' => 'active']);
        $patient = Patient::create(['user_id' => $patientUser->id, 'full_name' => 'Người bệnh', 'phone' => '0901234567', 'status' => 'active']);
        $schedule = DoctorSchedule::create(['doctor_id' => $doctor->id, 'work_date' => today()->addDay(), 'shift_name' => 'Ca sáng', 'start_time' => '08:00', 'end_time' => '09:00', 'maximum_patients' => 2, 'booked_patients' => 0, 'status' => 'active']);
        return compact('patientUser', 'patient', 'doctor', 'schedule');
    }

    public function test_booking_increases_capacity_and_cancellation_restores_it()
    {
        $f = $this->fixtures(); $service = app(AppointmentService::class);
        $appointment = $service->book(['patient_id' => $f['patient']->id, 'doctor_id' => $f['doctor']->id, 'doctor_schedule_id' => $f['schedule']->id, 'appointment_time' => '08:00', 'reason' => 'Đau đầu', 'booking_source' => 'online'], $f['patientUser']);
        $this->assertSame(Appointment::STATUS_PENDING, $appointment->status);
        $this->assertSame(1, $f['schedule']->fresh()->booked_patients);
        $service->cancel($appointment, 'Thay đổi kế hoạch');
        $this->assertSame(0, $f['schedule']->fresh()->booked_patients);
    }

    public function test_same_doctor_slot_cannot_be_booked_twice()
    {
        $f = $this->fixtures(); $service = app(AppointmentService::class);
        $payload = ['patient_id' => $f['patient']->id, 'doctor_id' => $f['doctor']->id, 'doctor_schedule_id' => $f['schedule']->id, 'appointment_time' => '08:00', 'reason' => 'Khám bệnh', 'booking_source' => 'online'];
        $service->book($payload, $f['patientUser']);
        $this->expectException(ValidationException::class);
        $service->book($payload, $f['patientUser']);
    }
}
