<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_returns_aggregated_data()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->getJson('/api/dashboard');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success', 
                     'data' => [
                         'user', 'daily_quote', 'today_mood', 'statistics', 'latest_articles', 'recommended_articles', 'recent_chat'
                     ]
                 ]);
    }
}
