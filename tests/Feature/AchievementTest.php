<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\User;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\Achievement;

class AchievementTest extends TestCase
{
    use RefreshDatabase;
    use Achievement;

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

    /**
     * @test
    */
    public function it_returns_the_valid_response_for_a_user(): void
    {
        // Seeded data
        // LESSON_ACHIEVEMENTS = [
        //     1 => 'First Lesson Watched',
        //     5 => '5 Lessons Watched',
        //     10 => '10 Lessons Watched',
        //     25 => '25 Lessons Watched',
        //     50 => '50 Lessons Watched',
        // ];

        // COMMENT_ACHIEVEMENTS = [
        //     1 => 'First Comment Written',
        //     3 => '3 Comments Written',
        //     5 => '5 Comments Written',
        //     10 => '10 Comments Written',
        //     20 => '20 Comments Written',
        // ];

        $this->createComments(1);
        $this->createWatchedLessons(6);

        $response = $this->get("/users/{$this->user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '5 Lessons Watched'
            ],
            'next_available_achievements' => [
                '3 Comments Written',
               '10 Lessons Watched'
            ],
            'current_badge' => BadgeSeeder::BEGINNER,
            'next_badge' => BadgeSeeder::INTERMEDIATE,
            'remaining_to_unlock_next_badge' => 1
        ]);
    }
}
