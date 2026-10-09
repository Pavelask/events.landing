<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::create([
            'name' => 'API User',
            'email' => 'api-user@example.com',
            'password' => bcrypt('secret123'),
        ]);
    }

    public function test_login_returns_token_and_user(): void
    {
        $this->makeUser();

        $this->postJson('/api/v1/auth/login', [
            'email' => 'api-user@example.com',
            'password' => 'secret123',
            'device_name' => 'flutter-test',
        ])
            ->assertOk()
            ->assertJsonStructure(['data' => ['token', 'token_type', 'user' => ['id', 'email']]])
            ->assertJsonPath('data.token_type', 'Bearer');
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $this->makeUser();

        $this->postJson('/api/v1/auth/login', [
            'email' => 'api-user@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(422);
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/v1/me')->assertUnauthorized();
    }

    public function test_me_returns_profile_with_token(): void
    {
        $user = $this->makeUser();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/me')
            ->assertOk()
            ->assertJsonPath('data.email', 'api-user@example.com');
    }

    public function test_logout_revokes_current_token(): void
    {
        $user = $this->makeUser();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/auth/logout')->assertOk();

        $this->app['auth']->forgetGuards();

        $this->withToken($token)->getJson('/api/v1/me')->assertUnauthorized();
    }
}
