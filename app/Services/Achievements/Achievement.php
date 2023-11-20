<?php

namespace App\Services\Achievements;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Services\Achievements\CommentWritten as CommentWrittenService;
use App\Services\Achievements\LessonWatched as LessonWatchedService;
use App\Models\User;

class Achievement implements Contracts\Achievement
{
    protected array $achievementServices = [
       CommentWritten::class => CommentWrittenService::class,
       LessonWatched::class => LessonWatchedService::class
    ];

    public function unlock(User $user, $event): bool
    {
        $eventClass = get_class($event);

        if (isset($this->achievementServices[$eventClass])) {
            $achievementServiceClass = $this->achievementServices[$eventClass];
            $achievementService = new $achievementServiceClass();
            return $achievementService->unlock($user);
        }

        return false;
    }

    public function unlockedAchievements(User $user): array
    {
        return $user->achievements
            ->pluck('name')
            ->toArray();
    }

    public function nextAvailableAchievements(User $user): array
    {
        $nextAvailableAchievements = [];

        foreach ($this->achievementServices as $achievementServiceClass) {
            $achievementService = new $achievementServiceClass();
            $nextAvailableAchievements[] = $achievementService->nextAvailableAchievements($user);
        }

        return $nextAvailableAchievements;
    }
}
