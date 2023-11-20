<?php

namespace App\Providers;

use App\Services\Achievements\Achievement as AchievementService;
use App\Services\Achievements\Contracts\Achievement as AchievementContract;
use App\Services\Badge\Badge as BadgeService;
use App\Services\Badge\Contracts\Badge as BadgeServiceContract;
use Illuminate\Support\ServiceProvider;

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
