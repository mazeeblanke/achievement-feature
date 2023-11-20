<?php

namespace App\Services\Achievements;

use App\Models\User;
use App\Models\AchievementType;
use App\Events\AchievementUnlocked;
use Database\Seeders\AchievementSeeder;
use App\Models\Achievement;
use App\Services\Achievements\Contracts\AchievementType as AchievementContract;

class CommentWritten implements AchievementContract
{
    public function nextAvailableAchievements(User $user): string
    {
        $type = $this->getAchievementType();

        $latestAchievement = $user->achievements()
            ->where('achievement_type_id', $type->id)
            ->orderBy('qualifier');
            
        return '';
    }

    public function unlock(User $user): bool
    {
        $totalComments = $user->comments()->count();

        $unlockableAchievements = $this->getUnlockableAchievements(
            $this->getAchievementType(),
            $user,
            $totalComments
        );

        // Unlock all applicable achievements
        foreach ($unlockableAchievements as $achievement) {
            $user->achievements()->attach($achievement->id);
            AchievementUnlocked::dispatch((string)$achievement->name, $user);
        }

        return $unlockableAchievements->count() > 0;
    }

    private function getAchievementType(): AchievementType
    {
        $achievementType = AchievementType::whereName(
            AchievementSeeder::COMMENT_TYPE
        )->first();

        if (!$achievementType) {
            throw new \Exception('Comment Achievement type not found');
        }

        return $achievementType;
    }

    private function getUnlockableAchievements(AchievementType $achievementType, User $user, int $totalComments)
    {
        $allAchievements = Achievement::where('achievement_type_id', $achievementType->id)
            ->orderBy('qualifier')
            ->get();

        $unlockedAchievements = $user->achievements
            ->pluck('id')
            ->toArray();

        return $allAchievements->filter(
            fn ($achievement) =>
            !in_array($achievement->id, $unlockedAchievements) &&
            $totalComments >= $achievement->qualifier
        );
    }
}
