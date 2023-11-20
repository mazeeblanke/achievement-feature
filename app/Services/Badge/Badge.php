<?php

namespace App\Services\Badge;

use App\Models\User;
use App\Events\BadgeUnlocked;
use App\Models\Badge as ModelsBadge;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Badge\Contracts\Badge as BadgeContract;

class Badge implements BadgeContract
{
    public function getCurrentBadge(User $user): string
    {
        $badge = $user->badge;

        if(!$badge) {
            $defaultBadge = ModelsBadge::where('no_of_achievements', 0)->first();
            $user->badge()->associate($defaultBadge);
        }

        return $user->fresh()->badge->name ?? '';
    }

    public function getNextBadge(User $user): string
    {
        $currentBadge = $user->badge;
        $numberOfAchievements = $currentBadge->no_of_achievements ?? 0;

        $badges = ModelsBadge::where('no_of_achievements', '>', $numberOfAchievements)
            ->orderBy('no_of_achievements')
            ->get();

        $newBadge = $badges->first();

        return $newBadge ? $newBadge->name : '';
    }

    /**
     *
     * @return Collection<int, ModelsBadge>
     */
    private function getBadges(ModelsBadge|null $currentBadge)
    {
        $currentNumberOfAchievements = $currentBadge->no_of_achievements ?? 0;

        return ModelsBadge::where('no_of_achievements', '>', $currentNumberOfAchievements)
            ->orderBy('no_of_achievements')
            ->get();
    }

    public function unlock(User $user): bool
    {
        $numberOfAchievements = $user->achievements()->count();
        $currentBadge = $user->badge;

        $badges = $this->getBadges($currentBadge);

        $newBadge = $badges->first(
            fn ($badge) =>
            $badge->no_of_achievements <= $numberOfAchievements
        );

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

        $badges = $this->getBadges($currentBadge);

        $nextBadge = $badges->first(
            fn ($badge) =>
            $badge->no_of_achievements >= $numberOfAchievements
        );

        return $nextBadge ? $nextBadge->no_of_achievements - $numberOfAchievements : 0;
    }
}
