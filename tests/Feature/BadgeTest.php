<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Comment;
use App\Events\LessonWatched;
use App\Events\CommentWritten;
use Database\Seeders\BadgeSeeder;
use App\Listeners\UnlockLessonWatchedAchievement;
use App\Listeners\UnlockCommentWrittenAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Achievements\LessonWatched as LessonWatchedService;
use App\Services\Achievements\CommentWritten as CommentWrittenService;

class BadgeTest extends TestCase
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

    private function createComments(int $count): void
    {
        Comment::factory($count)->make([
            'user_id' => $this->user->id,
        ])->each(function ($comment) {
            $comment->save();

            $event = new CommentWritten($comment);

            $listener = new UnlockCommentWrittenAchievement(
                new CommentWrittenService()
            );

            $listener->handle($event);
        });
    }

    private function createWatchedLessons(int $count): void
    {
        Lesson::factory($count)->make()->each(function ($lesson) {
            $lesson->save();

            $this->user->watched()->attach($lesson->id, [
                'watched' => 1,
            ]);

            $event = new LessonWatched($lesson, $this->user->fresh());

            $listener = new UnlockLessonWatchedAchievement(
                new LessonWatchedService()
            );

            $listener->handle($event);
        });
    }

    /** @test */
    public function a_new_badge_is_unlocked_when_a_user_achieves_the_required_achievements()
    {
        $this->createComments(2);

        $this->assertEquals(BadgeSeeder::BEGINNER, $this->user->badge->name);

        $this->createComments(9);

        $this->assertEquals(BadgeSeeder::INTERMEDIATE, $this->user->fresh()->badge->name);

        $this->createWatchedLessons(55);

        $this->createComments(20);

        $this->assertEquals(BadgeSeeder::MASTER, $this->user->fresh()->badge->name);
    }
}
