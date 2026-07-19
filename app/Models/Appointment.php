<?php
namespace App\Models;

use App\Support\GeneratesCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use GeneratesCode, SoftDeletes;
    const STATUS_PENDING = 'pending'; const STATUS_CONFIRMED = 'confirmed'; const STATUS_CHECKED_IN = 'checked_in'; const STATUS_WAITING = 'waiting'; const STATUS_IN_PROGRESS = 'in_progress'; const STATUS_COMPLETED = 'completed'; const STATUS_CANCELLED = 'cancelled'; const STATUS_NO_SHOW = 'no_show';
    const SOURCE_ONLINE = 'online'; const SOURCE_RECEPTIONIST = 'receptionist';
    protected $fillable = ['appointment_code', 'patient_id', 'specialty_id', 'doctor_id', 'doctor_schedule_id', 'medical_service_id', 'appointment_date', 'appointment_time', 'reason', 'symptoms_note', 'booking_source', 'status', 'cancellation_reason', 'confirmed_by', 'confirmed_at', 'created_by'];
    protected $casts = ['appointment_date' => 'date', 'confirmed_at' => 'datetime'];
    protected function getCodeColumn() { return 'appointment_code'; }
    protected function getCodePrefix() { return 'LH'; }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function specialty() { return $this->belongsTo(Specialty::class); }
    public function schedule() { return $this->belongsTo(DoctorSchedule::class, 'doctor_schedule_id'); }
    public function medicalService() { return $this->belongsTo(MedicalService::class); }
    public function ticket() { return $this->hasOne(ExaminationTicket::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
}
