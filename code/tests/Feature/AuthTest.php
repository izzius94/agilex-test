<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_login_if_the_user_does_not_exits(): void
    {
        $response = $this->postJson('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertInvalid(['email' => 'These credentials do not match our records.']);
    }

    public function test_cannot_login_with_wrong_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertInvalid(['email' => 'These credentials do not match our records.']);
    }

    public function test_user_is_logged_with_valid_credentials(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged in successfully.',
            ]);
    }
}
