<?php
namespace App\Models;

use App\Support\GeneratesCode;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use GeneratesCode;
    const STATUS_DRAFT = 'draft'; const STATUS_COMPLETED = 'completed';
    protected $fillable = ['record_code', 'examination_ticket_id', 'patient_id', 'doctor_id', 'chief_complaint', 'current_symptoms', 'medical_history', 'family_history', 'pulse', 'temperature', 'blood_pressure', 'weight', 'height', 'diagnosis', 'conclusion', 'treatment_plan', 'doctor_note', 'follow_up_date', 'status'];
    protected $casts = ['follow_up_date' => 'date'];
    protected function getCodeColumn() { return 'record_code'; }
    protected function getCodePrefix() { return 'BA'; }
    public function ticket() { return $this->belongsTo(ExaminationTicket::class, 'examination_ticket_id'); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function prescription() { return $this->hasOne(Prescription::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
}
