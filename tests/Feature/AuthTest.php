<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase {
    use RefreshDatabase;

    public function test_user_can_register() {
        $user = [
            'name' => 'Roman',
            'email' => 'roman@example.com',
            'password' => '$2y$12$2xEVA179AkJGGjfbE0feZeGMYSU.vhPRqn7eGk8gYBSEqe3o54IFe',
            'password_confirmation' => '$2y$12$2xEVA179AkJGGjfbE0feZeGMYSU.vhPRqn7eGk8gYBSEqe3o54IFe'
        ];

        $response = $this->post('/api/register', $user);
        $response->assertStatus(201);
        $response->assertJsonStructure(['user', 'token']);
        $this->assertDatabaseHas('users', ['email' => 'roman@example.com']);
    }
}
