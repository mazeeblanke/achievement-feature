<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Services\Achievements\Contracts\Achievement as AchievementService;

class UnlockCommentWrittenAchievement
{
    private AchievementService $achievement;

    /**
     * Create the event listener.
     */
    public function __construct(AchievementService $achievement)
    {
        $this->achievement = $achievement;
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $user = $event->comment->user;

        if ($user) {
            $this->achievement->unlock($user, $event);
        }
    }
}
