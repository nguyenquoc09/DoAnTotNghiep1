<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model
{
    use SoftDeletes;
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    protected $fillable = ['name', 'slug', 'description', 'image', 'status'];
    public function doctors() { return $this->hasMany(Doctor::class); }
    public function medicalServices() { return $this->hasMany(MedicalService::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
}
