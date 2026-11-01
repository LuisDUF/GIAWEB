<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_a_hashed_user_and_returns_an_access_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ana Fitness',
            'email' => 'ana@example.test',
            'password' => 'secret-pass',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('user.name', 'Ana Fitness')
            ->assertJsonPath('user.email', 'ana@example.test')
            ->assertJsonPath('user.is_admin', false)
            ->assertJsonStructure(['success', 'user' => ['id', 'name', 'email', 'is_admin']]);

        $user = User::where('email', 'ana@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('secret-pass', $user->password));
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_registration_rejects_an_existing_email(): void
    {
        User::factory()->create(['email' => 'duplicate@example.test']);

        $this->postJson('/api/register', [
            'name' => 'Otro usuario',
            'email' => 'duplicate@example.test',
            'password' => 'secret-pass',
        ])
            ->assertStatus(400)
            ->assertJsonPath('error.email.0', 'The email has already been taken.');
    }

    public function test_login_rejects_invalid_credentials_without_issuing_a_token(): void
    {
        User::factory()->create([
            'email' => 'user@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $this->postJson('/api/login', [
            'email' => 'user@example.test',
            'password' => 'wrong-password',
        ])
            ->assertStatus(400)
            ->assertJsonPath('error', 'Contraseña incorrecta');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_login_returns_a_token_for_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'user@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $this->postJson('/api/login', [
            'email' => 'user@example.test',
            'password' => 'correct-password',
        ])
            ->assertOk()
            ->assertJsonPath('user.email', 'user@example.test')
            ->assertJsonStructure(['success', 'user']);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_protected_user_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/user')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }

    public function test_regular_user_cannot_access_administrator_routes(): void
    {
        Sanctum::actingAs(User::factory()->create(['is_admin' => false]));

        $this->getJson('/api/v1/admin/routines')
            ->assertForbidden()
            ->assertJsonPath('error', 'Acceso denegado. Se requieren permisos de administrador.');
    }

    public function test_administrator_can_access_administrator_routes(): void
    {
        Sanctum::actingAs(User::factory()->create(['is_admin' => true]));

        $this->getJson('/api/v1/admin/routines')
            ->assertOk()
            ->assertJsonStructure(['data', 'current_page', 'per_page']);
    }
}
