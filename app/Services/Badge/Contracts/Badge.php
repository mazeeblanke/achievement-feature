<?php

namespace App\Services\Badge\Contracts;

use App\Models\User;

Interface Badge
{
    public function unlock(User $user): bool;
}
