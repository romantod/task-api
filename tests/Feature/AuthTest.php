<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase {
    use RefreshDatabase;

    public function test_user_can_register() {
        $data = [
            'name' => 'Roman',
            'email' => 'newuser@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['user', 'token']);
        $this->assertDatabaseHas('users', ['email' => 'newuser@test.com']);
    }

    public function test_user_can_login() {
        $user = User::factory()->create(['password' => Hash::make('12345678')]);
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'token']);
    }

    public function test_login_fails_with_wrong_password() {
        $user = User::factory()->create([
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_logout() {
        $user = User::factory()->create([
            'password' => Hash::make('12345678'),
        ]);

        $token = $user->createToken('test')->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');
        $response->assertStatus(200);
        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $user->id]);
    }

    public function test_unauthenticated_user_cannot_access_protected_route() {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);
    }
}
