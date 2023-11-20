<?php

namespace App\Services\Achievements;

use App\Models\User;
use App\Models\Achievement;
use App\Models\AchievementType;
use App\Events\AchievementUnlocked;
use Database\Seeders\AchievementSeeder;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Achievements\Contracts\AchievementType as AchievementContract;

class LessonWatched implements AchievementContract
{
    public function nextAvailableAchievements(User $user): string
    {
        $type = $this->getAchievementType();
        $nextAchievement = '';

        $latestAchievement = $user->achievements()
            ->where('achievement_type_id', $type->id)
            ->orderByDesc('qualifier')
            ->first();

        if(!$latestAchievement) {
            return $nextAchievement;
        }

        $achievement = Achievement::where('achievement_type_id', $type->id)
            ->where('qualifier', '>', $latestAchievement->qualifier)
            ->orderBy('qualifier')
            ->first();

        $nextAchievement = $achievement->name ?? '';

        return $nextAchievement;
    }

    public function unlock(User $user): bool
    {
        $totalWatched = $user->watched()->count();

        $unlockableAchievements = $this->getUnlockableAchievements(
            $this->getAchievementType(),
            $user,
            $totalWatched
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
            AchievementSeeder::LESSON_TYPE
        )->first();

        if (!$achievementType) {
            throw new \Exception('Lesson Achievement type not found');
        }

        return $achievementType;
    }

    /**
     * Get all achievements that can be unlocked
     *
     * @return Collection<int, Achievement>
    */
    private function getUnlockableAchievements(AchievementType $achievementType, User $user, int $totalWatched)
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
            $totalWatched >= $achievement->qualifier
        );
    }
}
