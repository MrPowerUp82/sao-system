<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_subscription_is_redirected_to_pricing(): void
    {
        $user = User::factory()->create(['email' => 'nosub@example.com']);

        $response = $this->actingAs($user)->get('/player');

        $response->assertRedirect(route('player.subscription.pricing'));
    }

    public function test_subscribed_user_can_access_dashboard(): void
    {
        $user = User::factory()->create(['email' => 'sub@example.com']);
        $user->subscriptions()->create([
            'type' => 'default',
            'stripe_id' => 'sub_test_dash',
            'stripe_status' => 'active',
            'stripe_price' => 'price_test',
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->get('/player');

        $response->assertStatus(200);
    }

    public function test_pricing_page_renders_for_unsubscribed_user(): void
    {
        $user = User::factory()->create(['email' => 'pricing@example.com']);

        $response = $this->actingAs($user)->get(route('player.subscription.pricing'));

        $response->assertStatus(200);
    }

    public function test_mock_checkout_creates_subscription_outside_production(): void
    {
        $user = User::factory()->create(['email' => 'checkout@example.com']);

        $response = $this->actingAs($user)->post(route('player.subscription.checkout'));

        $response->assertRedirect(route('player.subscription.success'));
        $this->assertTrue($user->fresh()->subscribed('default'));
    }

    public function test_complimentary_subscription_grants_access(): void
    {
        $user = User::factory()->create(['email' => 'staff@example.com']);

        \App\Services\SubscriptionService::grantComplimentary($user);

        $this->assertTrue($user->fresh()->subscribed('default'));
        $this->actingAs($user)->get('/player')->assertStatus(200);
    }

    public function test_complimentary_subscription_with_expiry_expires(): void
    {
        $user = User::factory()->create(['email' => 'staff2@example.com']);

        $subscription = \App\Services\SubscriptionService::grantComplimentary($user, now()->addDays(7));

        $this->assertTrue($user->fresh()->subscribed('default'));

        // Simula o vencimento da cortesia
        $subscription->update(['ends_at' => now()->subMinute()]);

        $this->assertFalse($user->fresh()->subscribed('default'));
        $this->actingAs($user)->get('/player')->assertRedirect(route('player.subscription.pricing'));
    }

    public function test_complimentary_subscription_cannot_be_granted_twice(): void
    {
        $user = User::factory()->create(['email' => 'staff3@example.com']);

        \App\Services\SubscriptionService::grantComplimentary($user);

        $this->expectException(\RuntimeException::class);

        \App\Services\SubscriptionService::grantComplimentary($user);
    }

    public function test_mock_checkout_is_blocked_in_production(): void
    {
        $this->app['env'] = 'production';
        // Com env=production o Laravel volta a validar CSRF; não é o alvo deste teste
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $user = User::factory()->create(['email' => 'prod@example.com']);

        $response = $this->actingAs($user)->post(route('player.subscription.checkout'));

        $response->assertRedirect(route('player.subscription.pricing'));
        $this->assertFalse($user->fresh()->subscribed('default'));
    }
}
