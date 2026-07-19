<?php

namespace App\Models;

use App\Support\GeneratesCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use GeneratesCode, SoftDeletes;
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    protected $fillable = ['user_id', 'specialty_id', 'doctor_code', 'degree', 'academic_title', 'years_of_experience', 'biography', 'consultation_fee', 'room_number', 'status'];
    protected $casts = ['consultation_fee' => 'decimal:2'];
    protected function getCodeColumn() { return 'doctor_code'; }
    protected function getCodePrefix() { return 'BS'; }
    public function user() { return $this->belongsTo(User::class); }
    public function specialty() { return $this->belongsTo(Specialty::class); }
    public function schedules() { return $this->hasMany(DoctorSchedule::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function examinationTickets() { return $this->hasMany(ExaminationTicket::class); }
    public function medicalRecords() { return $this->hasMany(MedicalRecord::class); }
    public function prescriptions() { return $this->hasMany(Prescription::class); }
}
