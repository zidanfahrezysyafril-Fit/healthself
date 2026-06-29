<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mood;

class MoodApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_mood()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->postJson('/api/moods', [
                             'mood' => 'Senang',
                             'note' => 'Hari yang baik',
                             'sleep_hours' => 8,
                             'stress_level' => 2
                         ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('moods', ['user_id' => $user->id, 'mood' => 'Senang']);
    }

    public function test_can_get_statistics()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->getJson('/api/moods/statistics');

        $response->assertStatus(200);
    }
}
