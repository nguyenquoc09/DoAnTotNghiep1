<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;
    public function test_home_login_and_registration_pages_are_available()
    {
        $this->get('/')->assertOk()->assertSee('Phòng khám An Tâm');
        $this->get('/dang-nhap')->assertOk();
        $this->get('/dang-ky')->assertOk();
    }
}
