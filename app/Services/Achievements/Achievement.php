<?php

namespace App\Services\Achievements;

use App\Models\User;
use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Services\Achievements\LessonWatched as LessonWatchedService;
use App\Services\Achievements\CommentWritten as CommentWrittenService;

class Achievement implements Contracts\Achievement
{
    /**
     *
     * @var array<string, string>
     */
    protected $achievementServices = [
        CommentWritten::class => CommentWrittenService::class,
        LessonWatched::class => LessonWatchedService::class,
    ];

    public function unlock(User $user, Object $event): bool
    {
        $eventClass = get_class($event);

        if (isset($this->achievementServices[$eventClass])) {
            $achievementServiceClass = $this->achievementServices[$eventClass];
            $achievementService = new $achievementServiceClass();

            if ($achievementService instanceof Contracts\AchievementType) {
                return $achievementService->unlock($user);
            }
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

            if($achievementService instanceof Contracts\AchievementType) {
                $nextAvailableAchievements[] = $achievementService->nextAvailableAchievements($user);
            }
        }

        return $nextAvailableAchievements;
    }
}
