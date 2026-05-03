<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login()
    {
        $response = $this->get('/mahasiswa');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login Admin');
    }

    public function test_admin_can_login_with_correct_credentials()
    {
        $admin = Admin::create([
            'username' => 'admin_test',
            'password' => Hash::make('password_test'),
        ]);

        $response = $this->post('/login', [
            'username' => 'admin_test',
            'password' => 'password_test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/mahasiswa');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_cannot_login_with_incorrect_password()
    {
        Admin::create([
            'username' => 'admin_test',
            'password' => Hash::make('password_test'),
        ]);

        $response = $this->post('/login', [
            'username' => 'admin_test',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('username');
        $this->assertGuest('admin');
    }

    public function test_authenticated_admin_can_access_mahasiswa_page()
    {
        $admin = Admin::create([
            'username' => 'admin_test',
            'password' => Hash::make('password_test'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/mahasiswa');

        $response->assertStatus(200);
        $response->assertSee('Daftar Mahasiswa');
        $response->assertSee($admin->username);
    }

    public function test_admin_can_logout()
    {
        $admin = Admin::create([
            'username' => 'admin_test',
            'password' => Hash::make('password_test'),
        ]);

        $response = $this->actingAs($admin, 'admin')->post('/logout');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest('admin');
    }
}
