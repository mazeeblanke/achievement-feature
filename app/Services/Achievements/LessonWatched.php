<?php

namespace App\Services\Achievements;

use App\Models\User;
use App\Models\Achievement;
use App\Models\AchievementType;
use App\Events\AchievementUnlocked;
use Database\Seeders\AchievementSeeder;
use App\Services\Achievements\Contracts\Achievement as AchievementContract;

class LessonWatched implements AchievementContract
{
    public function unlock(User $user): bool
    {
        $totalWatched = $user->watched()->count();

        $achievementType = AchievementType::whereName(
            AchievementSeeder::LESSON_TYPE
        )->first();

        if (!$achievementType) {
            throw new \Exception('Lesson Achievement Type not found');
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
            $totalWatched >= $achievement->qualifier
        );

        // Unlock all applicable achievements
        foreach ($unlockedAchievements as $achievement) {
            $user->achievements()->attach($achievement->id);
            AchievementUnlocked::dispatch((string)$achievement->name, $user);
        }

        return $unlockedAchievements->count() > 0;
    }
}
