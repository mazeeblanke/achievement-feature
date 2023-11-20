<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    // Define constants for badge names
    public const BEGINNER = 'Beginner';
    public const INTERMEDIATE = 'Intermediate';
    public const ADVANCED = 'Advanced';
    public const MASTER = 'Master';

    public const BADGES = [
        0 => self::BEGINNER,
        4 => self::INTERMEDIATE,
        8 => self::ADVANCED,
        10 => self::MASTER,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::BADGES as $noOfAchievements => $name) {
            Badge::firstOrCreate([
                'name' => $name,
                'no_of_achievements' => $noOfAchievements,
            ]);
        }
    }
}
