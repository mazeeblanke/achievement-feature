<?php

namespace App\Services\Badge\Contracts;

use App\Models\User;

interface Badge
{
    public function unlock(User $user): bool;

    public function getNextBadge(User $user): string;

    public function getCurrentBadge(User $user): string;

    public function achievementsCountTillNextBadge(User $user): int;
}
