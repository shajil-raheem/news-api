<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Faker\Factory as Faker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * testing normal user register
     */
    public function test_user_register(): void
    {
        $user = User::factory()->make();
        $response = $this->postJson('/api/user', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '123456',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    /**
     * testing user register with empty input
     */
    public function test_user_register_empty_input(): void
    {
        $response = $this->postJson('/api/user', []);
        $response->assertStatus(422);
    }

    /**
     * testing user register with invalid email
     */
    public function test_user_register_invalid_email(): void
    {
        $user = User::factory()->make();
        $response = $this->postJson('/api/user', [
            'name' => $user->name,
            'email' => 'an invalid mail',
            'password' => '123456',
        ]);
        $response->assertStatus(422);
    }

    /**
     * testing unauthorized view profile
     */
    public function test_get_user_details_unauthorized(): void
    {
        $response = $this->getJson('api/user');
        $response->assertStatus(401);
    }

    /**
     * testing authorized view profile
     */
    public function test_get_user_details_authorized(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->getJson('api/user');
        $response->assertStatus(200);
    }
}
