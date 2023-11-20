<?php

namespace App\Services\Badge;

use App\Events\BadgeUnlocked;
use App\Models\Badge as ModelsBadge;
use App\Models\User;
use App\Services\Badge\Contracts\Badge as BadgeContract;
use Illuminate\Support\Facades\Log;

class Badge implements BadgeContract
{
    public function unlock(User $user): bool
    {
        $numberOfAchievements = $user->achievements()->count();
        $currentBadge = $user->badge;
        $currentNumberOfAchievements = $currentBadge->no_of_achievements ?? 0;

        $badges = ModelsBadge::where('no_of_achievements', '>', $currentNumberOfAchievements)
            ->orderBy('no_of_achievements', 'desc')
            ->get();

        $newBadge = $badges->first(fn($badge) =>
            $badge->no_of_achievements <= $numberOfAchievements &&
            $currentBadge !== $badge
        );

        if ($newBadge) {
            $user->badge()->associate($newBadge);
            $user->save();
            BadgeUnlocked::dispatch($newBadge->name, $user);
        }

        return $currentBadge !== $newBadge;
    }
}
