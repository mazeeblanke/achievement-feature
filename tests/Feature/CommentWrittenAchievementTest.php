<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Listeners\UnlockCommentWrittenAchievement;
use App\Models\Achievement;
use App\Models\AchievementType;
use App\Models\Comment;
use App\Models\User;
use App\Services\Achievements\CommentWritten as CommentWrittenService;
use Database\Seeders\AchievementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentWrittenAchievementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Comment $comment;

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

    private function getAchievement(int $offset = 0): Achievement
    {
        $achievementType = AchievementType::whereName(
            AchievementSeeder::COMMENT_TYPE
        )->first();

        return Achievement::where('achievement_type_id', $achievementType->id)
            ->orderBy('qualifier')
            ->offset($offset)
            ->limit(1)
            ->first();
    }

    private function createComments(int $count): void
    {
        Comment::factory($count)->make([
            'user_id' => $this->user->id,
        ])->each(function ($comment) {
            $comment->save();

            $event = new CommentWritten($comment);

            $listener = new UnlockCommentWrittenAchievement(
                new CommentWrittenService
            );

            $listener->handle($event);
        });
    }

    /** @test */
    public function an_achievement_is_unlocked_when_a_comment_is_written(): void
    {
        Event::fake();

        $this->createComments(1);

        $this->assertDatabaseHas('achievement_user', [
            'user_id' => $this->user->id,
            'achievement_id' => $this->getAchievement()->id,
        ]);

        Event::assertDispatched(function(AchievementUnlocked $e) {
            return $e->achievementName === $this->getAchievement()->name;
        });
    }

    /** @test */
    public function achievements_are_unlocked_as_more_comments_are_written(): void
    {
        Event::fake();

        // creates first comment
        $this->createComments(1);

        // creates 4 more comments
        $this->createComments(4);

        // And creates 8 more comments
        $this->createComments(8);

        // There should be 4 achievements unlocked matching 1, 3, 5, 10
        Event::assertDispatched(AchievementUnlocked::class, 4);

        $this->assertDatabaseCount('achievement_user', 4);
    }
}
