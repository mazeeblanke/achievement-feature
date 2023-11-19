<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\AchievementType;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessonAchievement = AchievementType::firstOrCreate([
            'name' => 'lesson',
        ]);

        $commentAchievement = AchievementType::firstOrCreate([
            'name' => 'comment',
        ]);

        Achievement::firstOrCreate([
            'name' => 'First Lesson Watched',
            'achievement_type_id' => $lessonAchievement->id,
            'qualifier' => 1,
        ]);

        Achievement::firstOrCreate([
            'name' => '5 Lessons Watched',
            'achievement_type_id' => $lessonAchievement->id,
            'qualifier' => 5,
        ]);

        Achievement::firstOrCreate([
            'name' => '10 Lessons Watched',
            'achievement_type_id' => $lessonAchievement->id,
            'qualifier' => 10,
        ]);

        Achievement::firstOrCreate([
            'name' => '25 Lessons Watched',
            'achievement_type_id' => $lessonAchievement->id,
            'qualifier' => 25,
        ]);

        Achievement::firstOrCreate([
            'name' => '50 Lessons Watched',
            'achievement_type_id' => $lessonAchievement->id,
            'qualifier' => 50,
        ]);

        Achievement::firstOrCreate([
            'name' => 'First Comment Written',
            'achievement_type_id' => $commentAchievement->id,
            'qualifier' => 1,
        ]);

        Achievement::firstOrCreate([
            'name' => '3 Comments Written',
            'achievement_type_id' => $commentAchievement->id,
            'qualifier' => 3,
        ]);

        Achievement::firstOrCreate([
            'name' => '5 Comments Written',
            'achievement_type_id' => $commentAchievement->id,
            'qualifier' => 5,
        ]);

        Achievement::firstOrCreate([
            'name' => '10 Comments Written',
            'achievement_type_id' => $commentAchievement->id,
            'qualifier' => 10,
        ]);

        Achievement::firstOrCreate([
            'name' => '20 Comments Written',
            'achievement_type_id' => $commentAchievement->id,
            'qualifier' => 20,
        ]);
    }
}
