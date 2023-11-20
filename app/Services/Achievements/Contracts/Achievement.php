<?php

namespace App\Services\Achievements\Contracts;

use App\Models\User;

interface Achievement
{
    public function unlock(User $user): bool;
}
