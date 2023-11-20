<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create([
            'name' => 'test user',
            'email' => 'test@test.com',
        ]);

        $this->actingAs($this->user);
    }

    /**
     * @test
    */
    public function it_returns_user_achievements(): void
    {
        $response = $this->get("/users/{$this->user->id}/achievements");

        $response->assertStatus(200);
    }

    /**
     * @test
    */
    public function it_returns_the_required_fields(): void
    {
        $response = $this->get("/users/{$this->user->id}/achievements");

        $response->assertJsonStructure([
            'unlocked_achievements',
            'next_available_achievements',
            'current_badge',
            'next_badge',
            'remaining_to_unlock_next_badge'
        ]);
    }
}
