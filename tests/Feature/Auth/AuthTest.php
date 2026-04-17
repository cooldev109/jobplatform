<?php

namespace Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ana Silva',
            'email' => 'ana@example.com',
            'password' => 'Senha1234',
            'password_confirmation' => 'Senha1234',
            'user_type' => 'candidate',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['user' => ['id', 'name', 'email', 'user_type'], 'token']);

        $this->assertDatabaseHas('users', [
            'email' => 'ana@example.com',
            'user_type' => 'candidate',
        ]);
    }

    public function test_company_owner_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Carlos Empresa',
            'email' => 'carlos@empresa.com',
            'password' => 'Senha1234',
            'password_confirmation' => 'Senha1234',
            'user_type' => 'company_owner',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('users', ['user_type' => 'company_owner']);
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'dup@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Dup',
            'email' => 'dup@example.com',
            'password' => 'Senha1234',
            'password_confirmation' => 'Senha1234',
            'user_type' => 'candidate',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_registration_rejects_weak_password(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Weak',
            'email' => 'weak@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
            'user_type' => 'candidate',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('password');
    }

    public function test_registration_rejects_invalid_user_type(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Bad',
            'email' => 'bad@example.com',
            'password' => 'Senha1234',
            'password_confirmation' => 'Senha1234',
            'user_type' => 'admin',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('user_type');
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'Senha1234',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'Senha1234',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['user' => ['id', 'email'], 'token']);
    }

    public function test_login_rejects_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'Senha1234',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'wrong',
        ]);

        $response->assertUnauthorized();
    }

    public function test_me_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }

    public function test_authenticated_user_can_fetch_me(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_logout_revokes_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout')
            ->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
