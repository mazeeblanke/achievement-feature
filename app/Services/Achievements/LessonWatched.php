<?php

namespace App\Services\Achievements;

use App\Models\User;
use App\Services\Achievements\Contracts\Achievement;

class LessonWatched implements Achievement
{
    public function unlock(User $user): bool
    {
        // Unlock the achievement
        return true;
    }
}
