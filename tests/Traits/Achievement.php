<?php

namespace Tests\Traits;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\UnlockCommentWrittenAchievement;
use App\Listeners\UnlockLessonWatchedAchievement;
use App\Services\Achievements\Contracts\Achievement as AchievementService;
use App\Models\Comment;
use App\Models\Lesson;

trait Achievement
{
    private function createComments(int $count): void
    {
        Comment::factory($count)->make([
            'user_id' => $this->user->id,
        ])->each(function ($comment) {
            $comment->save();

            $event = new CommentWritten($comment);

            $listener = new UnlockCommentWrittenAchievement(
                resolve(AchievementService::class)
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
                resolve(AchievementService::class)
            );

            $listener->handle($event);
        });
    }
}
