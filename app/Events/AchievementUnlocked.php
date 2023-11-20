<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AchievementUnlocked
{
    use Dispatchable;
    use SerializesModels;

    public string $achievementName;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(string $achievementName, User $user)
    {
        $this->achievementName = $achievementName;
        $this->user = $user;
    }
}
