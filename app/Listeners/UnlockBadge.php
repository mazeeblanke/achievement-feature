<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Services\Badge\Badge as BadgeService;

class UnlockBadge
{
    private BadgeService $badgeService;

    /**
     * Create the event listener.
     */
    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event): void
    {
        $this->badgeService->unlock($event->user);
    }
}
