<?php

namespace Tests\Feature;

use App\Models\AchievementType;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Tests\Traits\Achievement;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;
    use Achievement;

    private User $user;

    private AchievementType $commentType;

    private AchievementType $lessonType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create([
            'name' => 'test user',
            'email' => 'test@test.com',
        ]);

        $this->actingAs($this->user);

        $this->commentType = AchievementType::whereName(
            AchievementSeeder::COMMENT_TYPE
        )->first();

        $this->lessonType = AchievementType::whereName(
            AchievementSeeder::LESSON_TYPE
        )->first();
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

    private function getAvalAchievements(int $comments, int $watchedLessons): array
    {
        $commentAchievements = $this->commentType->achievements->filter(fn($achievement) =>
            $achievement->qualifier <= $comments
        )->pluck('name');

        $lessonAchievements = $this->lessonType->achievements->filter(fn($achievement) =>
            $achievement->qualifier <= $watchedLessons
        )->pluck('name');

        return $commentAchievements->merge($lessonAchievements)->toArray();
    }

    private function getNextAchievements(int $comments, int $watchedLessons): array
    {   $nextAchievements = [];

        $nextLessonAchievement = $this->lessonType->achievements->filter(fn($achievement) =>
            $achievement->qualifier > $watchedLessons
        )->first();

        $nextCommentAchievement = $this->commentType->achievements->filter(fn($achievement) =>
            $achievement->qualifier > $comments
        )->first();

        if($nextCommentAchievement) {
            $nextAchievements[] = $nextCommentAchievement->name;
        }

        if ($nextLessonAchievement) {
            $nextAchievements[] = $nextLessonAchievement->name;
        }

        return $nextAchievements;
    }

    /**
     * @test
    */
    public function it_returns_the_valid_response_for_a_user(): void
    {
        $comments = 1;
        $watchedLessons = 6;

        $this->createComments($comments);
        $this->createWatchedLessons($watchedLessons);

        $user = $this->user->fresh();

        $response = $this->get("/users/{$user->id}/achievements");

        $avalAchievements = $this->getAvalAchievements($comments, $watchedLessons);

        $nextAchievements = $this->getNextAchievements($comments, $watchedLessons);

        $response->assertJson([
            'unlocked_achievements' => $avalAchievements,
            'next_available_achievements' => $nextAchievements,
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
        $comments = 15;
        $watchedLessons = 28;

        $this->createComments($comments);
        $this->createWatchedLessons($watchedLessons);

        $user = $this->user->fresh();

        $response = $this->get("/users/{$user->id}/achievements");

        $avalAchievements = $this->getAvalAchievements($comments, $watchedLessons);

        $nextAchievements = $this->getNextAchievements($comments, $watchedLessons);

        $response->assertJson([
            'unlocked_achievements' => $avalAchievements,
            'next_available_achievements' => $nextAchievements,
            'current_badge' => BadgeSeeder::ADVANCED,
            'next_badge' => BadgeSeeder::MASTER,
            'remaining_to_unlock_next_badge' => 2,
        ]);
    }
}
