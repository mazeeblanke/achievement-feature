<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Listeners\UnlockLessonWatchedAchievement;
use App\Models\Achievement;
use App\Models\AchievementType;
use App\Models\Lesson;
use App\Models\User;
use App\Services\Achievements\LessonWatched as LessonWatchedService;
use Database\Seeders\AchievementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LessonWatchedAchievementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Lesson $lesson;

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create([
            'name' => 'test user',
            'email' => 'test@test.com'
        ]);

        $this->actingAs($this->user);
    }

    private function createWatchedLessons(int $count): void
    {
        Lesson::factory($count)->make()->each(function ($lesson) {
            $lesson->save();

            $this->user->watched()->attach($lesson->id, [
                'watched' => 1,
            ]);

            $event = new LessonWatched($lesson, $this->user);

            $listener = new UnlockLessonWatchedAchievement(
                new LessonWatchedService
            );

            $listener->handle($event);
        });
    }

    private function getAchievement(int $offset = 0): Achievement
    {
        $achievementType = AchievementType::whereName(
            AchievementSeeder::LESSON_TYPE
        )->first();

        return Achievement::where('achievement_type_id', $achievementType->id)
            ->orderBy('qualifier')
            ->offset($offset)
            ->limit(1)
            ->first();
    }

    /**
     * @test
     */
    public function a_user_can_unlock_the_lesson_written_achievement(): void
    {
        Event::fake();

        $this->createWatchedLessons(1);

        $this->assertDatabaseHas('achievement_user', [
            'user_id' => $this->user->id,
            'achievement_id' => $this->getAchievement()->id,
        ]);

        Event::assertDispatched(function(AchievementUnlocked $e) {
            return $e->achievementName === $this->getAchievement()->name;
        });
    }
}
