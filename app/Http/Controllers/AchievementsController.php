<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Badge\Contracts\Badge as BadgeService;

class AchievementsController extends Controller
{
    public function index(User $user, BadgeService $badgeService): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'unlocked_achievements' => [],
            'next_available_achievements' => [],
            'current_badge' => $badgeService->getCurrentBadge($user),
            'next_badge' => $badgeService->getNextBadge($user),
            'remaining_to_unlock_next_badge' => $badgeService->achievementsCountTillNextBadge($user),
        ]);
    }
}
