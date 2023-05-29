<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase {
    use WithFaker, RefreshDatabase;

    public function test_login_user_requires_valid_email() {
        $password = 'jd%uH7fgh35s&@!DsHss';
        $user = User::factory()->create(['password' => $password]);

        $body = [
            'email' => 'wrong',
            'password' => $password
        ];

        $response = $this->postJson('api/inviter/login', $body);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('email');
    }

    public function test_login_user_requires_email_exists_in_db() {
        $password = 'jd%uH7fgh35s&@!DsHss';
        $user = User::factory()->create(['password' => $password]);

        $body = [
            'email' => $this->faker->email(),
            'password' => $password
        ];

        $response = $this->postJson('api/inviter/login', $body);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('email');
    }

    public function test_login_user_requires_valid_password() {
        $password = 'jd%uH7fgh35s&@!DsHss';
        $user = User::factory()->create(['password' => $password]);

        $body = [
            'email' => $this->faker->email(),
            'password' => 'bad'
        ];

        $response = $this->postJson('api/inviter/login', $body);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('password');
    }
    
    public function test_login_user_returns_api_token_and_id() {
        $password = 'jd%uH7fgh35s&@!DsHss';
        $user = User::factory()->create(['password' => $password]);

        $body = [
            'email' => $user->email,
            'password' => $password
        ];

        $response = $this->postJson('api/inviter/login', $body);
        $response->assertOk();

        $this->assertNotNull($response->getData()->data->token);
        $this->assertNotNull($response->getData()->data->id);
    }

    public function test_an_unauth_user_cannot_refresh_api_token() {
        $user = User::factory()->create();

        $response = $this->postJson('api/inviter/refresh', []);
        $response->assertStatus(401);
    }

    public function test_a_user_can_refresh_their_api_token() {
        $user = User::factory()->create();
        $token = (new AuthService())->issueNewToken($user);

        $response = $this->postJson('api/inviter/refresh', [], ['Authorization' => "Bearer {$token}"]);
        $response->assertOk();

        $this->assertNotEquals($token, $response->getData()->data->token);
    }
}
