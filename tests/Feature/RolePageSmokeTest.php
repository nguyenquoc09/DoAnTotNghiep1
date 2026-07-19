<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePageSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_role_pages_render_successfully()
    {
        $this->seed();
        $pages = [
            'admin@clinic.local' => ['/admin/dashboard', '/admin/users', '/admin/specialties', '/admin/doctors', '/admin/schedules', '/admin/services', '/admin/medicines', '/admin/settings', '/admin/reports'],
            'receptionist@clinic.local' => ['/receptionist/dashboard', '/receptionist/patients', '/receptionist/appointments', '/receptionist/invoices'],
            'doctor@clinic.local' => ['/doctor/dashboard'],
            'patient@clinic.local' => ['/patient/dashboard', '/patient/appointments', '/patient/appointments/create', '/patient/history', '/patient/invoices'],
        ];
        foreach ($pages as $email => $urls) {
            $this->actingAs(User::where('email', $email)->firstOrFail());
            foreach ($urls as $url) { $this->get($url)->assertOk(); }
        }
    }
}
