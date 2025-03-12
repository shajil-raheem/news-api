<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    /**
     * Auth API success when verified
     */
    public function test_auth_returns_token_when_verified(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('api/auth', [
            'email' => $user->email,
            'password' => '123456'
        ]);
        $response->assertStatus(201);
    }

    /**
     * A basic feature test example.
     */
    public function test_auth_returns_error_when_not_verified(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('api/auth', [
            'email' => $user->email,
            'password' => '000000'
        ]);
        $response->assertStatus(401);
    }

    /**
     * A basic feature test example.
     */
    public function test_auth_returns_validation_error_when_invalid_input_passed(): void
    {
        $response = $this->postJson('api/auth', [
            'email' => '',
            'password' => '000000'
        ]);
        $response->assertStatus(422);
    }
}
