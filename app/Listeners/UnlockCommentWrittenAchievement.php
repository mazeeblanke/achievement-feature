<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Services\Achievements\CommentWritten as CommentWrittenService;

class UnlockCommentWrittenAchievement
{
    private CommentWrittenService $achievement;

    /**
     * Create the event listener.
     */
    public function __construct(CommentWrittenService $achievement)
    {
        $this->achievement = $achievement;
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $this->achievement->unlock($event->comment->user);
    }
}
