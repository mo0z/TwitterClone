<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlockTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Redis::flushall();
    }

    /** @test */
    public function user_can_block_another_user()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.blocks.store', $user2->id), [
                'current_user_id' => $user->id,
                'user_id' => $user2->id,
            ]);

        $this->assertTrue($user->blockingUser($user2));
    }

    /** @test */
    public function user_is_unfollowed_when_blocked()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user2->id), [
                'followed_id' => $user2->id,
                'follower_id' => $user->id,
            ]);

        $this->actingAs($user)
            ->json('POST', route('users.blocks.store', $user2->id), [
                'current_user_id' => $user->id,
                'user_id' => $user2->id,
            ]);

        $this->assertTrue($user->blockingUser($user2));
        $this->assertFalse($user->followingUser($user2));
    }

    /** @test */
    public function user_can_unblock_a_blocked_user()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.blocks.store', $user2->id), [
                'current_user_id' => $user->id,
                'user_id' => $user2->id,
            ]);

        $this->actingAs($user)
            ->json('POST', route('users.blocks.destroy', $user2->id), [
                'current_user_id' => $user->id,
                'user_id' => $user2->id,
            ]);

        $this->assertFalse($user->blockingUser($user2));
    }

    /** @test */
    public function user_cant_block_themselves()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.blocks.store', $user->id), [
                'current_user_id' => $user->id,
                'user_id' => $user->id,
            ]);

        $this->assertFalse($user->blockingUser($user));
    }
}
