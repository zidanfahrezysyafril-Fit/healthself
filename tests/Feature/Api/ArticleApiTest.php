<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Artikel;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    public function test_can_fetch_articles()
    {
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
                         ->getJson('/api/articles');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    public function test_can_toggle_bookmark()
    {
        $article = Artikel::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
                         ->postJson("/api/articles/{$article->id}/bookmark");

        $response->assertStatus(200);
    }
}
