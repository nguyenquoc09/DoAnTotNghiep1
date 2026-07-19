<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const SLOT_MINUTES = 30;
    protected $fillable = ['doctor_id', 'work_date', 'shift_name', 'start_time', 'end_time', 'maximum_patients', 'booked_patients', 'room_number', 'status', 'note'];
    protected $casts = ['work_date' => 'date'];
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function availableSlots()
    {
        $start = Carbon::parse($this->work_date->format('Y-m-d') . ' ' . $this->start_time);
        $end = Carbon::parse($this->work_date->format('Y-m-d') . ' ' . $this->end_time)->subMinutes(self::SLOT_MINUTES);
        $booked = $this->appointments()->whereNotIn('status', [Appointment::STATUS_CANCELLED, Appointment::STATUS_NO_SHOW])->pluck('appointment_time')->map(function ($time) { return substr($time, 0, 5); })->all();
        return collect(CarbonPeriod::create($start, self::SLOT_MINUTES . ' minutes', $end))->map(function ($time) { return $time->format('H:i'); })->reject(function ($time) use ($booked) { return in_array($time, $booked, true); })->values();
    }
}
