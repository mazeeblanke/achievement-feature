<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked
{
    use Dispatchable, SerializesModels;

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
