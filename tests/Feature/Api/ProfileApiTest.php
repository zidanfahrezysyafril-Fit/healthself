<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->getJson('/api/profile');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data' => ['id', 'name', 'email', 'avatar', 'statistics']]);
    }

    public function test_can_update_avatar()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->postJson('/api/profile/avatar', [
                             'avatar_id' => 'avatar-1'
                         ]);

        $response->assertStatus(200);
        $this->assertEquals('avatar-1', $user->fresh()->avatar);
    }
}
