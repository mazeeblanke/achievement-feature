<?php

namespace App\Services\Achievements\Contracts;

use App\Models\User;

interface Achievement
{
    public function unlock(User $user, $event): bool;

    public function unlockedAchievements(User $user): array;

    public function nextAvailableAchievements(User $user): array;
}
