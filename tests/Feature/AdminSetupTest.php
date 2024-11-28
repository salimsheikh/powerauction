<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_to_setup_if_administrator_not_exists_exists()
    {
        // Simulate a request to the home page
        $response = $this->get('/login');

        // Assert redirection to the setup wizard
        $response->assertRedirect('/setup-wizard');
    }

    public function test_create_admin_user_via_setup_wizard()
    {
        // Simulate a POST request to the setup page to register an admin
        $response = $this->post('/setup-wizard', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',            
            'password_confirmation' => 'password',
            'role' => 'administrator',
        ]);

        // Assert redirection after successful registration
        $response->assertRedirect('/login');

        // Verify that an admin user is created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => 'administrator',
        ]);
    }
}
