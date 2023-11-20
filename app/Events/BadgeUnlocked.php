<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BadgeUnlocked
{
    use Dispatchable, SerializesModels;

    public string $badgeName;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(string $badgeName, User $user)
    {
        $this->badgeName = $badgeName;
        $this->user = $user;
    }
}
