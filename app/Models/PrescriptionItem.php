<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $fillable = ['prescription_id', 'medicine_id', 'quantity', 'dosage', 'frequency', 'duration', 'usage_time', 'instruction', 'unit_price', 'total_price'];
    protected $casts = ['unit_price' => 'decimal:2', 'total_price' => 'decimal:2'];
    public function prescription() { return $this->belongsTo(Prescription::class); }
    public function medicine() { return $this->belongsTo(Medicine::class); }
}
