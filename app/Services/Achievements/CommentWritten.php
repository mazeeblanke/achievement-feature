<?php

namespace App\Services\Achievements;

use App\Events\AchievementUnlocked;
use App\Models\Achievement as Achievement;
use App\Models\AchievementType;
use App\Models\User;
use App\Services\Achievements\Contracts\Achievement as AchievementContract;

class CommentWritten implements AchievementContract
{
    public function unlock(User $user): bool
    {
        $totalComments = $user->comments()->count();
        $commentAchievementType = AchievementType::where('name', 'comment')->first();

        if (!$commentAchievementType) {
            throw new \Exception('Achievement type not found');
        }

        $achievements = Achievement::where('achievement_type_id', $commentAchievementType->id)
            ->orderBy('qualifier', 'desc')
            ->get();

        $unlockedAchievement = $achievements->first(function ($achievement) use ($totalComments, $user) {
            if ($totalComments >= $achievement->qualifier) {
                $user->achievements()->attach($achievement->id);
                return true;
            }
        });

        if($unlockedAchievement) {
            AchievementUnlocked::dispatch((string)$unlockedAchievement->name, $user);
            return true;
        }

        return false;
    }
}
