<?php

namespace App\Services\Achievements\Contracts;

use App\Models\User;

interface AchievementType
{
    public function unlock(User $user): bool;

    public function unlockedAchievements(User $user): array;

    public function nextAvailableAchievements(User $user): array;
}
