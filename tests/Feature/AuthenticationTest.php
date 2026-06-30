<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-001: User Login - Valid Credentials
     * Objective: Verify successful login with valid email and password
     */
    public function test_user_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'admin@gallery.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'aktif'
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@gallery.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /**
     * TC-002: User Login - Invalid Credentials
     * Objective: Verify login fails with incorrect password
     */
    public function test_user_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'admin@gallery.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@gallery.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * TC-003: User Login - Non-existent User
     * Objective: Verify login fails for non-existent email
     */
    public function test_login_fails_for_non_existent_user()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@gallery.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * TC-004: User Login - Account Status Non-Aktif
     * Objective: Verify non-aktif users cannot login
     */
    public function test_non_aktif_user_cannot_login()
    {
        User::factory()->create([
            'email' => 'inactive@gallery.com',
            'password' => bcrypt('password123'),
            'status' => 'non_aktif'
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@gallery.com',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * TC-005: User Logout
     * Objective: Verify successful logout and session destruction
     */
    public function test_user_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * TC-006: Login Page Display
     * Objective: Verify login page loads correctly
     */
    public function test_login_page_displays_correctly()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * TC-007: Authenticated User Cannot Access Login Page
     * Objective: Verify logged-in users are redirected from login page
     */
    public function test_authenticated_user_cannot_access_login_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/dashboard');
    }

    /**
     * TC-008: Password is Case Sensitive
     * Objective: Verify password comparison is case-sensitive
     */
    public function test_password_is_case_sensitive()
    {
        User::factory()->create([
            'email' => 'admin@gallery.com',
            'password' => bcrypt('Password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@gallery.com',
            'password' => 'password123', // lowercase p
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * TC-009: Email Format Validation
     * Objective: Verify invalid email formats are rejected
     */
    public function test_invalid_email_format_rejected()
    {
        $response = $this->post('/login', [
            'email' => 'invalidemail',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * TC-010: Empty Email Field Validation
     * Objective: Verify empty email is rejected
     */
    public function test_empty_email_field_rejected()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * TC-011: Empty Password Field Validation
     * Objective: Verify empty password is rejected
     */
    public function test_empty_password_field_rejected()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gallery.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * TC-012: Session Created After Successful Login
     * Objective: Verify session is properly created with timeout
     */
    public function test_session_created_after_login()
    {
        $user = User::factory()->create([
            'email' => 'admin@gallery.com',
            'password' => bcrypt('password123')
        ]);

        $this->post('/login', [
            'email' => 'admin@gallery.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $this->assertEquals($user->id, auth()->id());
    }
}
