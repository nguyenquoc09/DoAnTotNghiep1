<?php
namespace App\Services;

use App\Models\Appointment;
use App\Models\ExaminationTicket;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExaminationService
{
    public function start(ExaminationTicket $ticket, User $doctorUser)
    {
        if (!$doctorUser->doctor || $ticket->doctor_id !== $doctorUser->doctor->id) { abort(403); }
        return DB::transaction(function () use ($ticket) {
            $locked = ExaminationTicket::whereKey($ticket->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== ExaminationTicket::STATUS_WAITING) {
                throw ValidationException::withMessages(['status' => 'Phiếu khám không ở trạng thái chờ.']);
            }
            $locked->update(['status' => ExaminationTicket::STATUS_IN_PROGRESS, 'started_at' => now()]);
            $locked->appointment()->update(['status' => Appointment::STATUS_IN_PROGRESS]);
            return MedicalRecord::firstOrCreate(['examination_ticket_id' => $locked->id], [
                'patient_id' => $locked->patient_id, 'doctor_id' => $locked->doctor_id, 'status' => MedicalRecord::STATUS_DRAFT,
            ]);
        });
    }

    public function save(MedicalRecord $record, array $data) { $record->update($data); return $record; }

    public function complete(MedicalRecord $record, array $data)
    {
        if (empty($data['diagnosis']) || empty($data['conclusion'])) {
            throw ValidationException::withMessages(['diagnosis' => 'Chẩn đoán và kết luận là bắt buộc.']);
        }
        return DB::transaction(function () use ($record, $data) {
            $record->update(array_merge($data, ['status' => MedicalRecord::STATUS_COMPLETED]));
            $record->ticket()->update(['status' => ExaminationTicket::STATUS_COMPLETED, 'completed_at' => now()]);
            $record->ticket->appointment()->update(['status' => Appointment::STATUS_COMPLETED]);
            return $record;
        });
    }
}
