<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tests\Traits\Achievement;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'remaining_to_unlock_next_badge',
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

        $user = $this->user->fresh();

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '5 Lessons Watched',
            ],
            'next_available_achievements' => [
                '3 Comments Written',
                '10 Lessons Watched',
            ],
            'current_badge' => BadgeSeeder::BEGINNER,
            'next_badge' => BadgeSeeder::INTERMEDIATE,
            'remaining_to_unlock_next_badge' => 1,
        ]);
    }

    /**
     * @test
    */
    public function it_returns_the_valid_response_for_a_user_on_more_achievements(): void
    {
        $this->createComments(15);
        $this->createWatchedLessons(28);

        $user = $this->user->fresh();

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => [
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
            ],
            'next_available_achievements' => [
                '20 Comments Written',
                '50 Lessons Watched',
            ],
            'current_badge' => BadgeSeeder::ADVANCED,
            'next_badge' => BadgeSeeder::MASTER,
            'remaining_to_unlock_next_badge' => 2,
        ]);
    }
}
