<?php
namespace Tests\Feature\Receptionist;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\ExaminationTicket;
use App\Models\MedicalRecord;
use App\Models\MedicalService;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\User;
use App\Services\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_uses_server_prices_deducts_stock_once_and_supports_partial_payment()
    {
        $doctorRole = Role::create(['name' => 'Bác sĩ', 'code' => 'doctor']);
        $staffRole = Role::create(['name' => 'Lễ tân', 'code' => 'receptionist']);
        $doctorUser = User::create(['role_id' => $doctorRole->id, 'name' => 'Bác sĩ', 'email' => 'doctor@test.local', 'password' => bcrypt('password'), 'status' => 'active']);
        $staff = User::create(['role_id' => $staffRole->id, 'name' => 'Lễ tân', 'email' => 'staff@test.local', 'password' => bcrypt('password'), 'status' => 'active']);
        $specialty = Specialty::create(['name' => 'Nội khoa', 'slug' => 'noi-khoa', 'status' => 'active']);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialty_id' => $specialty->id, 'consultation_fee' => 200000, 'status' => 'active']);
        $patient = Patient::create(['full_name' => 'Người bệnh', 'phone' => '0901234567', 'status' => 'active']);
        $serviceModel = MedicalService::create(['specialty_id' => $specialty->id, 'service_code' => 'DV0001', 'name' => 'Khám nội', 'price' => 300000, 'status' => 'active']);
        $schedule = DoctorSchedule::create(['doctor_id' => $doctor->id, 'work_date' => today(), 'shift_name' => 'Ca sáng', 'start_time' => '08:00', 'end_time' => '09:00', 'maximum_patients' => 2, 'status' => 'active']);
        $appointment = Appointment::create(['patient_id' => $patient->id, 'specialty_id' => $specialty->id, 'doctor_id' => $doctor->id, 'doctor_schedule_id' => $schedule->id, 'medical_service_id' => $serviceModel->id, 'appointment_date' => today(), 'appointment_time' => '08:00', 'reason' => 'Khám bệnh', 'status' => 'completed']);
        $ticket = ExaminationTicket::create(['appointment_id' => $appointment->id, 'patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'queue_number' => 1, 'examination_date' => today(), 'status' => 'completed']);
        $record = MedicalRecord::create(['examination_ticket_id' => $ticket->id, 'patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'diagnosis' => 'Cảm cúm', 'conclusion' => 'Điều trị ngoại trú', 'status' => 'completed']);
        $medicine = Medicine::create(['medicine_code' => 'TH0001', 'name' => 'Paracetamol', 'unit' => 'Viên', 'selling_price' => 2000, 'stock_quantity' => 20, 'status' => 'active']);
        $prescription = Prescription::create(['medical_record_id' => $record->id, 'patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'prescribed_date' => today(), 'status' => 'completed']);
        $prescription->items()->create(['medicine_id' => $medicine->id, 'quantity' => 5, 'dosage' => '1 viên', 'unit_price' => 2000, 'total_price' => 10000]);
        $service = app(InvoiceService::class);
        $invoice = $service->create($record, $staff, 10000);
        $this->assertSame(300000.0, (float) $invoice->total_amount);
        $this->assertSame(15, $medicine->fresh()->stock_quantity);
        $service->pay($invoice, ['amount' => 100000, 'payment_method' => 'cash'], $staff);
        $this->assertSame('partially_paid', $invoice->fresh()->payment_status);
        $service->pay($invoice->fresh(), ['amount' => 200000, 'payment_method' => 'bank_transfer'], $staff);
        $this->assertSame('paid', $invoice->fresh()->payment_status);
    }
}
