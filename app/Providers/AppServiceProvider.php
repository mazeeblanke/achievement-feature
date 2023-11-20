<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Badge\Badge as BadgeService;
use App\Services\Badge\Contracts\Badge as BadgeServiceContract;
use App\Services\Achievements\Achievement as AchievementService;
use App\Services\Achievements\Contracts\Achievement as AchievementContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AchievementContract::class, AchievementService::class);
        $this->app->bind(BadgeServiceContract::class, BadgeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
