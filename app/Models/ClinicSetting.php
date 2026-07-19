<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicSetting extends Model
{
    protected $fillable = ['clinic_name', 'phone', 'email', 'address', 'logo', 'opening_time', 'closing_time', 'examination_policy'];
}
