<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::firstOrCreate([
            'name' => 'Beginner',
            'no_of_achievement' => 0,
        ]);

        Badge::firstOrCreate([
            'name' => 'Intermediate',
            'no_of_achievement' => 4,
        ]);

        Badge::firstOrCreate([
            'name' => 'Advanced',
            'no_of_achievement' => 8,
        ]);

        Badge::firstOrCreate([
            'name' => 'Master',
            'no_of_achievement' => 10,
        ]);
    }
}
