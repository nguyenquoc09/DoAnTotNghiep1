<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_LOCKED = 'locked';

    protected $fillable = ['role_id', 'name', 'email', 'phone', 'password', 'avatar', 'status', 'last_login_at'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['last_login_at' => 'datetime'];

    public function role() { return $this->belongsTo(Role::class); }
    public function doctor() { return $this->hasOne(Doctor::class); }
    public function patient() { return $this->hasOne(Patient::class); }
    public function isActive() { return $this->status === self::STATUS_ACTIVE; }
    public function hasRole($role) { return $this->role && $this->role->code === $role; }
    public function dashboardRoute()
    {
        $code = $this->role ? $this->role->code : null;
        return $code ? $code . '.dashboard' : 'home';
    }
}
