<?php
namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private function admin($status = 'active')
    {
        $role = Role::create(['name' => 'Quản trị viên', 'code' => Role::ADMIN]);
        return User::create(['role_id' => $role->id, 'name' => 'Admin', 'email' => 'admin@test.local', 'password' => Hash::make('Password123!'), 'status' => $status]);
    }

    public function test_active_user_can_login_and_is_redirected_to_role_dashboard()
    {
        $this->admin();
        $this->post('/dang-nhap', ['email' => 'admin@test.local', 'password' => 'Password123!'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_wrong_password_is_rejected()
    {
        $this->admin();
        $this->post('/dang-nhap', ['email' => 'admin@test.local', 'password' => 'wrong-password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_locked_account_cannot_login()
    {
        $this->admin(User::STATUS_LOCKED);
        $this->post('/dang-nhap', ['email' => 'admin@test.local', 'password' => 'Password123!'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
