<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ADMIN = 'admin';
    const RECEPTIONIST = 'receptionist';
    const DOCTOR = 'doctor';
    const PATIENT = 'patient';

    protected $fillable = ['name', 'code', 'description'];
    public function users() { return $this->hasMany(User::class); }
}
