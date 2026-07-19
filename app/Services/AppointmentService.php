<?php
namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\ExaminationTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    private $logs;

    public function __construct(ActivityLogService $logs) { $this->logs = $logs; }

    public function book(array $data, User $actor)
    {
        return DB::transaction(function () use ($data, $actor) {
            $schedule = DoctorSchedule::whereKey($data['doctor_schedule_id'])->lockForUpdate()->firstOrFail();
            $date = Carbon::parse($schedule->work_date)->startOfDay();
            $time = Carbon::parse($date->format('Y-m-d') . ' ' . $data['appointment_time']);
            $start = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
            $end = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);
            if ($date->lt(today()) || $time->lt(now()) || $schedule->status !== DoctorSchedule::STATUS_ACTIVE) {
                throw ValidationException::withMessages(['appointment_time' => 'Lịch làm việc không còn hiệu lực.']);
            }
            if ((int) $schedule->doctor_id !== (int) $data['doctor_id'] || $time->lt($start) || $time->copy()->addMinutes(DoctorSchedule::SLOT_MINUTES)->gt($end)) {
                throw ValidationException::withMessages(['appointment_time' => 'Khung giờ không thuộc lịch của bác sĩ.']);
            }
            if ($schedule->booked_patients >= $schedule->maximum_patients) {
                throw ValidationException::withMessages(['doctor_schedule_id' => 'Ca khám đã đủ số lượng bệnh nhân.']);
            }
            $occupied = Appointment::where('doctor_id', $schedule->doctor_id)
                ->whereDate('appointment_date', $date)
                ->whereTime('appointment_time', $time->format('H:i:s'))
                ->whereNotIn('status', [Appointment::STATUS_CANCELLED, Appointment::STATUS_NO_SHOW])
                ->lockForUpdate()->exists();
            if ($occupied) {
                throw ValidationException::withMessages(['appointment_time' => 'Khung giờ vừa được người khác đặt.']);
            }
            $duplicate = Appointment::where('patient_id', $data['patient_id'])->where('doctor_id', $schedule->doctor_id)
                ->whereDate('appointment_date', $date)->whereTime('appointment_time', $time->format('H:i:s'))
                ->whereNotIn('status', [Appointment::STATUS_CANCELLED, Appointment::STATUS_NO_SHOW])->exists();
            if ($duplicate) {
                throw ValidationException::withMessages(['appointment_time' => 'Bệnh nhân đã có lịch trùng giờ với bác sĩ này.']);
            }
            $source = isset($data['booking_source']) ? $data['booking_source'] : Appointment::SOURCE_ONLINE;
            $appointment = Appointment::create(array_merge($data, [
                'specialty_id' => $schedule->doctor->specialty_id,
                'appointment_date' => $date,
                'appointment_time' => $time->format('H:i:s'),
                'booking_source' => $source,
                'status' => $source === Appointment::SOURCE_RECEPTIONIST ? Appointment::STATUS_CONFIRMED : Appointment::STATUS_PENDING,
                'confirmed_by' => $source === Appointment::SOURCE_RECEPTIONIST ? $actor->id : null,
                'confirmed_at' => $source === Appointment::SOURCE_RECEPTIONIST ? now() : null,
                'created_by' => $actor->id,
            ]));
            $schedule->increment('booked_patients');
            $this->logs->write('create', 'appointment', 'Tạo lịch hẹn ' . $appointment->appointment_code, $actor);
            return $appointment;
        });
    }

    public function confirm(Appointment $appointment, User $actor)
    {
        if ($appointment->status !== Appointment::STATUS_PENDING) {
            throw ValidationException::withMessages(['status' => 'Chỉ lịch đang chờ mới có thể xác nhận.']);
        }
        $appointment->update(['status' => Appointment::STATUS_CONFIRMED, 'confirmed_by' => $actor->id, 'confirmed_at' => now()]);
        $this->logs->write('confirm', 'appointment', 'Xác nhận lịch hẹn ' . $appointment->appointment_code, $actor);
        return $appointment;
    }

    public function cancel(Appointment $appointment, $reason)
    {
        return DB::transaction(function () use ($appointment, $reason) {
            $locked = Appointment::whereKey($appointment->id)->lockForUpdate()->firstOrFail();
            if (!in_array($locked->status, [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED], true)) {
                throw ValidationException::withMessages(['status' => 'Lịch đã tiếp nhận hoặc hoàn tất không thể hủy.']);
            }
            $schedule = DoctorSchedule::whereKey($locked->doctor_schedule_id)->lockForUpdate()->firstOrFail();
            $locked->update(['status' => Appointment::STATUS_CANCELLED, 'cancellation_reason' => $reason]);
            if ($schedule->booked_patients > 0) { $schedule->decrement('booked_patients'); }
            $this->logs->write('cancel', 'appointment', 'Hủy lịch hẹn ' . $locked->appointment_code, auth()->user());
            return $locked;
        });
    }

    public function checkIn(Appointment $appointment, User $actor)
    {
        return DB::transaction(function () use ($appointment, $actor) {
            $locked = Appointment::whereKey($appointment->id)->lockForUpdate()->firstOrFail();
            if (!in_array($locked->status, [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED], true)) {
                throw ValidationException::withMessages(['status' => 'Lịch hẹn không ở trạng thái có thể tiếp nhận.']);
            }
            $lastQueue = ExaminationTicket::where('doctor_id', $locked->doctor_id)->whereDate('examination_date', today())->lockForUpdate()->max('queue_number');
            $ticket = ExaminationTicket::create([
                'appointment_id' => $locked->id, 'patient_id' => $locked->patient_id, 'doctor_id' => $locked->doctor_id,
                'queue_number' => ((int) $lastQueue) + 1, 'examination_date' => today(), 'check_in_at' => now(),
                'status' => ExaminationTicket::STATUS_WAITING, 'created_by' => $actor->id,
            ]);
            $locked->update(['status' => Appointment::STATUS_WAITING]);
            $this->logs->write('check_in', 'appointment', 'Tiếp nhận lịch hẹn ' . $locked->appointment_code, $actor);
            return $ticket;
        });
    }
}
