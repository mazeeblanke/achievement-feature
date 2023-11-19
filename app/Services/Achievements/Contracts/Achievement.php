<?php

namespace App\Services\Achievements\Contracts;

use App\Models\User;

Interface Achievement
{
    public function unlock(User $user): bool;
}
