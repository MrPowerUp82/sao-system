<?php

namespace Tests\Feature;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuildChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_member_cannot_fetch_messages(): void
    {
        $user = User::factory()->create(['email' => 'member1@example.com']);
        $otherUser = User::factory()->create(['email' => 'member2@example.com']);
        
        $guild = Guild::create([
            'name' => 'KoB',
            'master_id' => $otherUser->id,
            'invite_code' => 'ABCDEFGH'
        ]);

        $response = $this->actingAs($user)->getJson("/player/guild/{$guild->id}/messages");

        $response->assertStatus(403);
    }

    public function test_member_can_fetch_messages(): void
    {
        $user = User::factory()->create(['email' => 'member1@example.com']);
        $guild = Guild::create([
            'name' => 'KoB',
            'master_id' => $user->id,
            'invite_code' => 'ABCDEFGH'
        ]);
        $guild->members()->attach($user->id, ['role' => 'master']);

        // Create a message
        $guild->messages()->create([
            'user_id' => $user->id,
            'message' => 'Welcome to Aincrad!'
        ]);

        $response = $this->actingAs($user)->getJson("/player/guild/{$guild->id}/messages");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.message', 'Welcome to Aincrad!');
        $response->assertJsonPath('0.user.name', $user->name);
    }

    public function test_non_member_cannot_send_messages(): void
    {
        $user = User::factory()->create(['email' => 'member1@example.com']);
        $otherUser = User::factory()->create(['email' => 'member2@example.com']);
        
        $guild = Guild::create([
            'name' => 'KoB',
            'master_id' => $otherUser->id,
            'invite_code' => 'ABCDEFGH'
        ]);

        $response = $this->actingAs($user)->postJson("/player/guild/{$guild->id}/messages", [
            'message' => 'Hello there!'
        ]);

        $response->assertStatus(403);
    }

    public function test_member_can_send_messages(): void
    {
        $user = User::factory()->create(['email' => 'member1@example.com']);
        $guild = Guild::create([
            'name' => 'KoB',
            'master_id' => $user->id,
            'invite_code' => 'ABCDEFGH'
        ]);
        $guild->members()->attach($user->id, ['role' => 'master']);

        $response = $this->actingAs($user)->postJson("/player/guild/{$guild->id}/messages", [
            'message' => 'Hello KoB!'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Hello KoB!');
        $this->assertDatabaseHas('guild_messages', [
            'guild_id' => $guild->id,
            'user_id' => $user->id,
            'message' => 'Hello KoB!'
        ]);
    }
}
