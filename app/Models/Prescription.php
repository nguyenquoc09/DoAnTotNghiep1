<?php
namespace App\Models;

use App\Support\GeneratesCode;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use GeneratesCode;
    const STATUS_DRAFT = 'draft'; const STATUS_COMPLETED = 'completed';
    protected $fillable = ['prescription_code', 'medical_record_id', 'patient_id', 'doctor_id', 'prescribed_date', 'general_instruction', 'status'];
    protected $casts = ['prescribed_date' => 'date'];
    protected function getCodeColumn() { return 'prescription_code'; }
    protected function getCodePrefix() { return 'DT'; }
    public function medicalRecord() { return $this->belongsTo(MedicalRecord::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function items() { return $this->hasMany(PrescriptionItem::class); }
}
