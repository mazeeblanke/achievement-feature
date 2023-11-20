<?php

namespace App\Services\Achievements\Contracts;

use App\Models\User;

interface AchievementType
{
    public function unlock(User $user): bool;

    public function nextAvailableAchievements(User $user): string;
}
