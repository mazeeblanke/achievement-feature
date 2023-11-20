<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Services\Achievements\Contracts\Achievement as AchievementService;

class UnlockLessonWatchedAchievement
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
    public function handle(LessonWatched $event): void
    {
        $this->achievement->unlock($event->user, $event);
    }
}
