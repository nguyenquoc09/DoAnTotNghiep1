<?php
namespace App\Policies;

use App\Models\Patient;
use App\Models\Role;
use App\Models\User;

class PatientPolicy
{
    public function view(User $user, Patient $patient)
    {
        if (in_array($user->role->code, [Role::ADMIN, Role::RECEPTIONIST], true)) { return true; }
        if ($user->hasRole(Role::PATIENT)) { return $patient->user_id === $user->id; }
        return $user->doctor && $patient->appointments()->where('doctor_id', $user->doctor->id)->exists();
    }
}
