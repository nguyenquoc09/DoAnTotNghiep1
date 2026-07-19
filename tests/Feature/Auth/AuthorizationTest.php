<?php
namespace Tests\Feature\Auth;

use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_cannot_access_admin_routes()
    {
        $role = Role::create(['name' => 'Bệnh nhân', 'code' => Role::PATIENT]);
        $user = User::create(['role_id' => $role->id, 'name' => 'Bệnh nhân', 'email' => 'patient@test.local', 'password' => bcrypt('Password123!'), 'status' => 'active']);
        Patient::create(['user_id' => $user->id, 'full_name' => 'Bệnh nhân', 'phone' => '0901234567', 'status' => 'active']);
        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/patient/dashboard')->assertOk();
    }
}
