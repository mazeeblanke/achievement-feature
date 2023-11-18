<?php

namespace Tests\Unit;

use App\Models\AchievementType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementTypeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $name = 'First Achievement type';

        $achievementType = AchievementType::factory()->create([
            'name' => $name,
        ]);

        $this->assertEquals($name, $achievementType->name);
        $this->assertDatabaseHas('achievement_types', [
            'name' => $achievementType->name,
        ]);
    }
}
