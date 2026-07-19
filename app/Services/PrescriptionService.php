<?php
namespace App\Services;

use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Prescription;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PrescriptionService
{
    public function save(MedicalRecord $record, array $data)
    {
        return DB::transaction(function () use ($record, $data) {
            $items = isset($data['items']) ? $data['items'] : [];
            $medicineIds = array_column($items, 'medicine_id');
            if (count($medicineIds) !== count(array_unique($medicineIds))) {
                throw ValidationException::withMessages(['items' => 'Một thuốc không được xuất hiện nhiều lần trong đơn.']);
            }
            $prescription = Prescription::updateOrCreate(['medical_record_id' => $record->id], [
                'patient_id' => $record->patient_id, 'doctor_id' => $record->doctor_id, 'prescribed_date' => today(),
                'general_instruction' => isset($data['general_instruction']) ? $data['general_instruction'] : null,
                'status' => Prescription::STATUS_COMPLETED,
            ]);
            $prescription->items()->delete();
            foreach ($items as $item) {
                $medicine = Medicine::whereKey($item['medicine_id'])->lockForUpdate()->firstOrFail();
                if ((int) $item['quantity'] > $medicine->stock_quantity) {
                    throw ValidationException::withMessages(['items' => "Thuốc {$medicine->name} không đủ tồn kho."]);
                }
                $prescription->items()->create(array_merge($item, [
                    'unit_price' => $medicine->selling_price,
                    'total_price' => $medicine->selling_price * (int) $item['quantity'],
                ]));
            }
            return $prescription->load('items.medicine');
        });
    }
}
