<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
    */
    public function it_has_a_name(): void
    {
        $name = 'First Achievement';

        $achievement = Achievement::factory()->create([
            'name' => $name,
        ]);

        $this->assertEquals($name, $achievement->name);
    }

    /**
     * @test
    */
    public function it_has_a_qualifier(): void
    {
        $qualifier = 10;

        $achievement = Achievement::factory()->create([
            'qualifier' => $qualifier,
        ]);

        $this->assertEquals($qualifier, $achievement->qualifier);
    }

    /**
     * @test
    */
    public function it_can_have_a_description(): void
    {
        $description = 'First Achievement description';

        $achievement = Achievement::factory()->create([
            'description' => $description,
        ]);

        $this->assertEquals($description, $achievement->description);
    }
}
