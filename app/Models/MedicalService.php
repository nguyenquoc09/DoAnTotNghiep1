<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalService extends Model
{
    use SoftDeletes;
    const STATUS_ACTIVE = 'active';
    protected $fillable = ['specialty_id', 'service_code', 'name', 'description', 'price', 'status'];
    protected $casts = ['price' => 'decimal:2'];
    public function specialty() { return $this->belongsTo(Specialty::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
}
