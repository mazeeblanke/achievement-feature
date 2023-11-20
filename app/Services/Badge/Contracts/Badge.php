<?php

namespace App\Services\Badge\Contracts;

use App\Models\User;

interface Badge
{
    public function unlock(User $user): bool;
}
