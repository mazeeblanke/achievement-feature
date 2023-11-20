<?php

namespace App\Services\Badge;

use App\Models\User;
use App\Events\BadgeUnlocked;
use App\Models\Badge as ModelsBadge;
use App\Services\Badge\Contracts\Badge as BadgeContract;

class Badge implements BadgeContract
{
    public function getCurrentBadge(User $user): string
    {
        return $user->badge->name;
    }

    public function getNextBadge(User $user): string
    {
        $currentBadge = $user->badge;

        $badges = ModelsBadge::where('no_of_achievements', '>', $currentBadge->no_of_achievements)
            ->orderBy('no_of_achievements')
            ->get();

        $newBadge = $badges->first();

        return $newBadge ? $newBadge->name : '';
    }

    private function getNewBadge(ModelsBadge $currentBadge, int $numberOfAchievements): ModelsBadge|null
    {
        $currentNumberOfAchievements = $currentBadge->no_of_achievements ?? 0;

        $badges = ModelsBadge::where('no_of_achievements', '>', $currentNumberOfAchievements)
            ->orderBy('no_of_achievements')
            ->get();

        return $badges->first(
            fn ($badge) =>
            $badge->no_of_achievements <= $numberOfAchievements
        );
    }

    public function unlock(User $user): bool
    {
        $numberOfAchievements = $user->achievements()->count();
        $currentBadge = $user->badge;

        $newBadge = $this->getNewBadge($currentBadge, $numberOfAchievements);

        if ($newBadge) {
            $user->badge()->associate($newBadge);
            $user->save();
            BadgeUnlocked::dispatch($newBadge->name, $user);
        }

        return $currentBadge !== $newBadge;
    }

    public function achievementsCountTillNextBadge(User $user): int
    {
        $numberOfAchievements = $user->achievements()->count();
        $currentBadge = $user->badge;

        $newBadge = $this->getNewBadge($currentBadge, $numberOfAchievements);

        return $newBadge ? $newBadge->no_of_achievements - $numberOfAchievements : 0;
    }
}
