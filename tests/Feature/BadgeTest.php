<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\Achievement;

class BadgeTest extends TestCase
{
    use RefreshDatabase;
    use Achievement;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create([
            'name' => 'test user',
            'email' => 'test@test.com',
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function a_new_badge_is_unlocked_when_a_user_achieves_the_required_achievements()
    {
        $this->createComments(2);

        $this->assertEquals(BadgeSeeder::BEGINNER, $this->user->badge->name);

        $this->createComments(9);

        $this->assertEquals(BadgeSeeder::INTERMEDIATE, $this->user->fresh()->badge->name);

        $this->createWatchedLessons(55);

        $this->createComments(20);

        $this->assertEquals(BadgeSeeder::MASTER, $this->user->fresh()->badge->name);
    }
}
