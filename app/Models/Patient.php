<?php

namespace App\Models;

use App\Support\GeneratesCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use GeneratesCode, SoftDeletes;
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    protected $fillable = ['user_id', 'patient_code', 'full_name', 'date_of_birth', 'gender', 'phone', 'email', 'identity_number', 'health_insurance_number', 'address', 'emergency_contact_name', 'emergency_contact_phone', 'blood_type', 'allergies', 'medical_history', 'status', 'created_by'];
    protected $casts = ['date_of_birth' => 'date'];
    protected function getCodeColumn() { return 'patient_code'; }
    protected function getCodePrefix() { return 'BN'; }
    public function user() { return $this->belongsTo(User::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function examinationTickets() { return $this->hasMany(ExaminationTicket::class); }
    public function medicalRecords() { return $this->hasMany(MedicalRecord::class); }
    public function prescriptions() { return $this->hasMany(Prescription::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('patient_code', 'like', "%{$term}%")
                ->orWhere('full_name', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }
}
