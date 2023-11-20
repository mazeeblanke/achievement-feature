<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Badge\Contracts\Badge as BadgeService;
use App\Services\Achievements\Contracts\Achievement as AchievementService;

class AchievementsController extends Controller
{
    /**
     * Get the achievements for the given user.
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function index(User $user, BadgeService $badgeService, AchievementService $achievementService)
    {
        return response()->json([
            'unlocked_achievements' => $achievementService->unlockedAchievements($user),
            'next_available_achievements' => $achievementService->nextAvailableAchievements($user),
            'current_badge' => $badgeService->getCurrentBadge($user),
            'next_badge' => $badgeService->getNextBadge($user),
            'remaining_to_unlock_next_badge' => $badgeService->achievementsCountTillNextBadge($user),
        ]);
    }
}
