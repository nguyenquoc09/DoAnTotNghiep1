<?php
namespace App\Models;

use App\Support\GeneratesCode;
use Illuminate\Database\Eloquent\Model;

class ExaminationTicket extends Model
{
    use GeneratesCode;
    const STATUS_WAITING = 'waiting'; const STATUS_IN_PROGRESS = 'in_progress'; const STATUS_COMPLETED = 'completed';
    protected $fillable = ['ticket_code', 'appointment_id', 'patient_id', 'doctor_id', 'queue_number', 'examination_date', 'check_in_at', 'started_at', 'completed_at', 'status', 'created_by'];
    protected $casts = ['examination_date' => 'date', 'check_in_at' => 'datetime', 'started_at' => 'datetime', 'completed_at' => 'datetime'];
    protected function getCodeColumn() { return 'ticket_code'; }
    protected function getCodePrefix() { return 'PK'; }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function medicalRecord() { return $this->hasOne(MedicalRecord::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
}
