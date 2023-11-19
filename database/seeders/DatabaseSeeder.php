<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $lessons = Lesson::factory()
        //     ->count(20)
        //     ->create();

        $this->call([
            AchievementSeeder::class,
            // UserSeeder::class,
            // CommentSeeder::class,
            // LessonSeeder::class,
            // LessonUserSeeder::class,
            // AchievementUserSeeder::class,
        ]);
    }
}
