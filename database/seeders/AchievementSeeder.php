<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\AchievementType;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public const LESSON_TYPE = 'lesson';
    public const COMMENT_TYPE = 'comment';

    public const LESSON_ACHIEVEMENTS = [
        1 => 'First Lesson Watched',
        5 => '5 Lessons Watched',
        10 => '10 Lessons Watched',
        25 => '25 Lessons Watched',
        50 => '50 Lessons Watched',
    ];

    public const COMMENT_ACHIEVEMENTS = [
        1 => 'First Comment Written',
        3 => '3 Comments Written',
        5 => '5 Comments Written',
        10 => '10 Comments Written',
        20 => '20 Comments Written',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLessonAchievements();
        $this->seedCommentAchievements();
    }

    private function seedCommentAchievements(): void
    {
        $commentAchievement = AchievementType::firstOrCreate([
            'name' => self::COMMENT_TYPE,
        ]);

        foreach (self::COMMENT_ACHIEVEMENTS as $qualifier => $name) {
            Achievement::firstOrCreate([
                'name' => $name,
                'achievement_type_id' => $commentAchievement->id,
                'qualifier' => $qualifier,
            ]);
        }
    }

    private function seedLessonAchievements(): void
    {
        $lessonAchievement = AchievementType::firstOrCreate([
            'name' => self::LESSON_TYPE,
        ]);

        foreach (self::LESSON_ACHIEVEMENTS as $qualifier => $name) {
            Achievement::firstOrCreate([
                'name' => $name,
                'achievement_type_id' => $lessonAchievement->id,
                'qualifier' => $qualifier,
            ]);
        }
    }
}
