<?php

namespace Tests\Feature\Models;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Listeners\UnlockCommentWrittenAchievement;
use App\Models\Achievement;
use App\Models\AchievementType;
use App\Models\Comment;
use App\Models\User;
use App\Services\Achievements\CommentWritten as AchievementsCommentWritten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AchievementTest extends TestCase
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

        $this->comment = Comment::factory()->create([
            'body' => 'test comment',
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user);
    }

    private function getFirstCommentAchievement() {
        $achievement_type = AchievementType::where('name', '=', 'comment')->first();
        return Achievement::where('achievement_type_id', '=', $achievement_type->id)->orderBy('qualifier', 'asc')->first();
    }

    /** @test */
    public function an_achievement_is_unlocked_when_a_comment_is_written()
    {
        Event::fake();

        $event = new CommentWritten($this->comment);

        $listener = new UnlockCommentWrittenAchievement(
            new AchievementsCommentWritten()
        );

        $listener->handle($event);

        $this->assertDatabaseHas('achievement_user', [
            'user_id' => $this->user->id,
            'achievement_id' => $this->getFirstCommentAchievement()->id,
        ]);

        Event::assertDispatched(AchievementUnlocked::class);
    }
}
