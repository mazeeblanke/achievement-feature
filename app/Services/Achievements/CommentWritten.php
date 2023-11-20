<?php

namespace App\Services\Achievements;

use App\Models\User;
use App\Models\AchievementType;
use App\Events\AchievementUnlocked;
use Database\Seeders\AchievementSeeder;
use App\Models\Achievement as Achievement;
use App\Services\Achievements\Contracts\Achievement as AchievementContract;

class CommentWritten implements AchievementContract
{
    public function unlock(User $user): bool
    {
        $totalComments = $user->comments()->count();

        $achievementType = AchievementType::whereName(
            AchievementSeeder::COMMENT_TYPE
        )->first();

        if (!$achievementType) {
            throw new \Exception('Comment Achievement type not found');
        }

        $achievements = Achievement::where('achievement_type_id', $achievementType->id)
            ->orderBy('qualifier')
            ->get();

        $unlockedAchievements = $user->achievements
            ->pluck('id')
            ->toArray();

        $unlockedAchievements = $achievements->filter(
            fn ($achievement) =>
            !in_array($achievement->id, $unlockedAchievements) &&
            $totalComments >= $achievement->qualifier
        );

        // Unlock all applicable achievements
        foreach ($unlockedAchievements as $achievement) {
            $user->achievements()->attach($achievement->id);
            AchievementUnlocked::dispatch((string)$achievement->name, $user);
        }

        return $unlockedAchievements->count() > 0;
    }
}
