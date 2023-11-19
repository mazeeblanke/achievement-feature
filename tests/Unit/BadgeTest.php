<?php

namespace Tests\Unit;

use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $name = 'Test Badge';

        $badge = Badge::factory()->create([
            'name' => $name,
        ]);

        $this->assertEquals($name, $badge->name);
        $this->assertDatabaseHas('badges', [
            'name' => $name,
        ]);
    }

    /**
     * @test
     */
    public function it_can_have_a_description(): void
    {
        $description = 'Test badge description';

        $badge = Badge::factory()->create([
            'description' => $description,
        ]);

        $this->assertEquals($description, $badge->description);
        $this->assertDatabaseHas('badges', [
            'description' => $description,
        ]);
    }

    /**
     * @test
     */
    public function it_has_no_achievements(): void
    {
        $noOfAchievements = 10;

        $badge = Badge::factory()->create([
            'no_of_achievements' => $noOfAchievements,
        ]);

        $this->assertEquals($noOfAchievements, $badge->no_of_achievements);
        
        $this->assertDatabaseHas('badges', [
            'no_of_achievements' => $noOfAchievements,
        ]);
    }
}
