<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WorkoutSessionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_workout_session_endpoints_require_authentication(): void
    {
        $this->getJson('/api/v1/workout-sessions')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }

    public function test_workout_session_rejects_an_invalid_payload_before_writing_data(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/workout-sessions', [
            'date' => 'not-a-date',
            'duration_minutes' => -1,
            'exercises' => [],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['date', 'duration_minutes', 'exercises']);
    }

    public function test_workout_session_cannot_assign_a_different_user(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/workout-sessions', [
            'user_id' => 'another-profile',
            'date' => '2026-10-29',
            'exercises' => [[
                'exercise_id' => 999999,
                'sets' => [[
                    'set_number' => 1,
                    'weight_kg' => 10,
                    'reps' => 10,
                ]],
            ]],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['user_id']);
    }

    public function test_account_without_profile_receives_a_clear_conflict_response(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/v1/workout-sessions')
            ->assertStatus(409)
            ->assertJsonPath('message', 'La cuenta no tiene un perfil asociado.');
    }
}
