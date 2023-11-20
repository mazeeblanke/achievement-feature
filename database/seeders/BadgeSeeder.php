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

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::firstOrCreate([
            'name' => self::BEGINNER,
            'no_of_achievements' => 0,
        ]);

        Badge::firstOrCreate([
            'name' => self::INTERMEDIATE,
            'no_of_achievements' => 4,
        ]);

        Badge::firstOrCreate([
            'name' => self::ADVANCED,
            'no_of_achievements' => 8,
        ]);

        Badge::firstOrCreate([
            'name' => self::MASTER,
            'no_of_achievements' => 10,
        ]);
    }
}
