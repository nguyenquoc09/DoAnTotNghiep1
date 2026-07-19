<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\ClinicSetting;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\ExaminationTicket;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\MedicalRecord;
use App\Models\MedicalService;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $roles = collect([
            Role::ADMIN => ['Quản trị viên', 'Quản lý toàn bộ hệ thống'],
            Role::RECEPTIONIST => ['Nhân viên lễ tân', 'Tiếp nhận bệnh nhân và thanh toán'],
            Role::DOCTOR => ['Bác sĩ', 'Khám bệnh và kê đơn'],
            Role::PATIENT => ['Bệnh nhân', 'Đặt lịch và theo dõi hồ sơ cá nhân'],
        ])->mapWithKeys(function ($value, $code) { $role = Role::create(['code' => $code, 'name' => $value[0], 'description' => $value[1]]); return [$code => $role]; });

        $admin = $this->user($roles[Role::ADMIN], 'Quản trị viên An Tâm', 'admin@clinic.local', '0901000001');
        $receptionist = $this->user($roles[Role::RECEPTIONIST], 'Nguyễn Minh Anh', 'receptionist@clinic.local', '0901000002');
        ClinicSetting::create(['clinic_name' => 'Phòng khám An Tâm', 'phone' => '19008686', 'email' => 'hello@antam.vn', 'address' => '125 Nguyễn Thị Minh Khai, Quận 3, TP. Hồ Chí Minh', 'opening_time' => '07:00', 'closing_time' => '20:00', 'examination_policy' => 'Vui lòng có mặt trước giờ hẹn 15 phút và mang theo giấy tờ tùy thân, thẻ bảo hiểm nếu có.']);

        $specialtyData = [
            ['Nội tổng quát', 'Khám, chẩn đoán và theo dõi các bệnh lý nội khoa thường gặp.'],
            ['Nhi khoa', 'Chăm sóc sức khỏe toàn diện cho trẻ sơ sinh, trẻ nhỏ và thanh thiếu niên.'],
            ['Tim mạch', 'Tầm soát và điều trị các bệnh lý tim mạch, huyết áp.'],
            ['Da liễu', 'Chẩn đoán và điều trị bệnh lý da, tóc và móng.'],
            ['Tai Mũi Họng', 'Điều trị bệnh lý tai, mũi, họng bằng phương pháp hiện đại.'],
        ];
        $specialties = collect($specialtyData)->map(function ($item) { return Specialty::create(['name' => $item[0], 'slug' => Str::slug($item[0]), 'description' => $item[1], 'status' => 'active']); });

        $doctorNames = ['Trần Hoàng Nam', 'Lê Thu Hà', 'Nguyễn Quốc Bảo', 'Phạm Ngọc Lan', 'Đỗ Minh Tuấn', 'Võ Thanh Hương', 'Bùi Gia Huy', 'Dương Thùy Linh', 'Hồ Đức Anh', 'Trịnh Mai Phương'];
        $doctors = collect($doctorNames)->map(function ($name, $index) use ($roles, $specialties) {
            $email = $index === 0 ? 'doctor@clinic.local' : 'doctor' . ($index + 1) . '@clinic.local';
            $user = $this->user($roles[Role::DOCTOR], $name, $email, '0912' . str_pad((string) $index, 6, '0', STR_PAD_LEFT));
            return Doctor::create(['user_id' => $user->id, 'specialty_id' => $specialties[$index % 5]->id, 'degree' => $index % 3 === 0 ? 'Bác sĩ chuyên khoa II' : 'Bác sĩ chuyên khoa I', 'academic_title' => $index === 0 ? 'ThS.BS.' : 'BS.', 'years_of_experience' => 6 + $index * 2, 'biography' => 'Bác sĩ giàu kinh nghiệm, luôn lắng nghe và xây dựng kế hoạch điều trị phù hợp với từng người bệnh.', 'consultation_fee' => 250000 + ($index % 5) * 50000, 'room_number' => 'P.' . (201 + $index), 'status' => 'active']);
        });

        $patientNames = ['Nguyễn Thị Thanh Mai', 'Trần Văn Hùng', 'Lê Minh Khang', 'Phạm Ngọc Ánh', 'Võ Hoàng Long', 'Đặng Thùy Trang', 'Bùi Anh Tuấn', 'Dương Mỹ Linh', 'Hồ Gia Bảo', 'Trịnh Thu Hương', 'Ngô Đức Minh', 'Lý Khánh Vy', 'Mai Quốc Việt', 'Cao Thanh Hà', 'Vũ Quang Huy', 'Tạ Bảo Ngọc', 'Đinh Tuấn Kiệt', 'Phan Như Ý', 'Châu Minh Quân', 'Lâm Ngọc Trâm'];
        $patients = collect($patientNames)->map(function ($name, $index) use ($roles, $receptionist) {
            $user = null;
            if ($index === 0) { $user = $this->user($roles[Role::PATIENT], $name, 'patient@clinic.local', '0988000001'); }
            return Patient::create(['user_id' => $user ? $user->id : null, 'full_name' => $name, 'date_of_birth' => now()->subYears(20 + $index)->subDays($index * 10), 'gender' => $index % 2 ? 'male' : 'female', 'phone' => '0988' . str_pad((string) ($index + 1), 6, '0', STR_PAD_LEFT), 'email' => $user ? $user->email : null, 'address' => (25 + $index) . ' đường Hoa Mai, TP. Hồ Chí Minh', 'blood_type' => ['A+', 'B+', 'O+', 'AB+'][$index % 4], 'allergies' => $index % 5 === 0 ? 'Dị ứng Penicillin' : null, 'medical_history' => $index % 4 === 0 ? 'Tiền sử viêm dạ dày' : null, 'status' => 'active', 'created_by' => $receptionist->id]);
        });

        $services = collect(['Khám tổng quát', 'Khám nhi chuyên sâu', 'Tầm soát tim mạch', 'Khám da liễu', 'Nội soi Tai Mũi Họng'])->map(function ($name, $index) use ($specialties) { return MedicalService::create(['specialty_id' => $specialties[$index]->id, 'service_code' => 'DV' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT), 'name' => $name, 'description' => 'Dịch vụ ' . mb_strtolower($name) . ' với quy trình nhẹ nhàng, rõ ràng.', 'price' => 250000 + $index * 75000, 'status' => 'active']); });
        $medicineNames = ['Paracetamol 500mg', 'Ibuprofen 400mg', 'Amoxicillin 500mg', 'Cetirizine 10mg', 'Omeprazole 20mg', 'Vitamin C 500mg', 'Loratadine 10mg', 'Azithromycin 250mg', 'Acetylcysteine 200mg', 'Domperidone 10mg', 'Metformin 500mg', 'Amlodipine 5mg', 'Losartan 50mg', 'Atorvastatin 10mg', 'Salbutamol 2mg', 'Dextromethorphan 15mg', 'Chlorpheniramine 4mg', 'Cefuroxime 500mg', 'Mupirocin 2%', 'ORS gói'];
        $medicines = collect($medicineNames)->map(function ($name, $index) { return Medicine::create(['medicine_code' => 'TH' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT), 'name' => $name, 'active_ingredient' => explode(' ', $name)[0], 'dosage_form' => $index === 18 ? 'Kem bôi' : ($index === 19 ? 'Bột pha' : 'Viên'), 'strength' => preg_replace('/^[^0-9]+/', '', $name), 'unit' => $index === 18 ? 'Tuýp' : ($index === 19 ? 'Gói' : 'Viên'), 'manufacturer' => 'Dược phẩm Việt Nam', 'purchase_price' => 800 + $index * 120, 'selling_price' => 1500 + $index * 250, 'stock_quantity' => 100 + $index * 5, 'minimum_stock' => 20, 'usage_instruction' => 'Dùng theo chỉ định của bác sĩ.', 'status' => 'active']); });

        foreach (range(0, 13) as $day) {
            foreach ($doctors as $index => $doctor) {
                DoctorSchedule::create(['doctor_id' => $doctor->id, 'work_date' => today()->addDays($day), 'shift_name' => $index % 2 ? 'Ca chiều' : 'Ca sáng', 'start_time' => $index % 2 ? '13:30' : '08:00', 'end_time' => $index % 2 ? '17:00' : '11:30', 'maximum_patients' => 7, 'booked_patients' => 0, 'room_number' => $doctor->room_number, 'status' => 'active']);
            }
        }

        $statuses = ['pending', 'confirmed', 'waiting', 'in_progress', 'completed', 'cancelled'];
        foreach (range(0, 11) as $index) {
            $doctor = $doctors[$index % $doctors->count()];
            $schedule = DoctorSchedule::where('doctor_id', $doctor->id)->whereDate('work_date', today()->addDays($index % 5))->first();
            $status = $statuses[$index % count($statuses)];
            $appointment = Appointment::create(['patient_id' => $patients[$index]->id, 'specialty_id' => $doctor->specialty_id, 'doctor_id' => $doctor->id, 'doctor_schedule_id' => $schedule->id, 'medical_service_id' => $services[$index % 5]->id, 'appointment_date' => $schedule->work_date, 'appointment_time' => $schedule->start_time, 'reason' => 'Khám và tư vấn tình trạng sức khỏe gần đây.', 'booking_source' => $index % 2 ? 'receptionist' : 'online', 'status' => $status, 'confirmed_by' => $status !== 'pending' ? $receptionist->id : null, 'confirmed_at' => $status !== 'pending' ? now() : null, 'created_by' => $index % 2 ? $receptionist->id : optional($patients[$index]->user)->id]);
            if ($status !== 'cancelled') { $schedule->increment('booked_patients'); }
            if (in_array($status, ['waiting', 'in_progress', 'completed'], true)) {
                $ticket = ExaminationTicket::create(['appointment_id' => $appointment->id, 'patient_id' => $appointment->patient_id, 'doctor_id' => $doctor->id, 'queue_number' => $index + 1, 'examination_date' => $appointment->appointment_date, 'check_in_at' => now(), 'started_at' => in_array($status, ['in_progress', 'completed'], true) ? now() : null, 'completed_at' => $status === 'completed' ? now() : null, 'status' => $status === 'completed' ? 'completed' : ($status === 'in_progress' ? 'in_progress' : 'waiting'), 'created_by' => $receptionist->id]);
                if ($status === 'completed') { $this->completedVisit($ticket, $medicines[$index % 10], $receptionist, $index); }
            }
        }
    }

    private function user(Role $role, $name, $email, $phone)
    {
        return User::create(['role_id' => $role->id, 'name' => $name, 'email' => $email, 'phone' => $phone, 'password' => Hash::make('Password123!'), 'status' => 'active']);
    }

    private function completedVisit(ExaminationTicket $ticket, Medicine $medicine, User $receptionist, $index)
    {
        $record = MedicalRecord::create(['examination_ticket_id' => $ticket->id, 'patient_id' => $ticket->patient_id, 'doctor_id' => $ticket->doctor_id, 'chief_complaint' => 'Mệt mỏi và đau đầu nhẹ', 'current_symptoms' => 'Triệu chứng xuất hiện trong ba ngày gần đây.', 'pulse' => 78, 'temperature' => 36.8, 'blood_pressure' => '120/80', 'weight' => 58, 'height' => 165, 'diagnosis' => 'Nhiễm siêu vi đường hô hấp trên', 'conclusion' => 'Tình trạng ổn định, điều trị ngoại trú.', 'treatment_plan' => 'Nghỉ ngơi, uống đủ nước và dùng thuốc theo đơn.', 'follow_up_date' => today()->addDays(7), 'status' => 'completed']);
        $prescription = Prescription::create(['medical_record_id' => $record->id, 'patient_id' => $record->patient_id, 'doctor_id' => $record->doctor_id, 'prescribed_date' => today(), 'general_instruction' => 'Uống thuốc sau ăn, tái khám nếu triệu chứng tăng.', 'status' => 'completed']);
        $item = $prescription->items()->create(['medicine_id' => $medicine->id, 'quantity' => 6, 'dosage' => '1 viên', 'frequency' => '2 lần/ngày', 'duration' => '3 ngày', 'usage_time' => 'Sau ăn', 'instruction' => 'Uống nhiều nước', 'unit_price' => $medicine->selling_price, 'total_price' => $medicine->selling_price * 6]);
        $service = $ticket->appointment->medicalService;
        $total = $service->price + $item->total_price;
        $invoice = Invoice::create(['patient_id' => $record->patient_id, 'appointment_id' => $ticket->appointment_id, 'examination_ticket_id' => $ticket->id, 'medical_record_id' => $record->id, 'service_amount' => $service->price, 'medicine_amount' => $item->total_price, 'discount_amount' => 0, 'total_amount' => $total, 'paid_amount' => $index % 2 ? 0 : $total, 'payment_status' => $index % 2 ? 'unpaid' : 'paid', 'created_by' => $receptionist->id, 'paid_at' => $index % 2 ? null : now(), 'stock_deducted_at' => now()]);
        $invoice->items()->create(['item_type' => InvoiceItem::TYPE_SERVICE, 'reference_id' => $service->id, 'description' => $service->name, 'quantity' => 1, 'unit_price' => $service->price, 'amount' => $service->price]);
        $invoice->items()->create(['item_type' => InvoiceItem::TYPE_MEDICINE, 'reference_id' => $medicine->id, 'description' => $medicine->name, 'quantity' => 6, 'unit_price' => $medicine->selling_price, 'amount' => $item->total_price]);
        $medicine->decrement('stock_quantity', 6);
        if ($invoice->payment_status === 'paid') { Payment::create(['invoice_id' => $invoice->id, 'amount' => $total, 'payment_method' => 'cash', 'payment_date' => now(), 'received_by' => $receptionist->id, 'status' => 'completed']); }
    }
}
