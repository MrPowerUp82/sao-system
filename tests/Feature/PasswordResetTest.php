<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_renders(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_link_is_sent_to_valid_email(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'reset@example.com']);

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'reset2@example.com']);

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'nova-senha-123',
                'password_confirmation' => 'nova-senha-123',
            ]);

            $response->assertRedirect(route('login'));

            $this->assertTrue(Hash::check('nova-senha-123', $user->fresh()->password));

            return true;
        });
    }

    public function test_password_reset_fails_with_invalid_token(): void
    {
        $user = User::factory()->create(['email' => 'reset3@example.com']);

        $response = $this->post('/reset-password', [
            'token' => 'token-invalido',
            'email' => $user->email,
            'password' => 'nova-senha-123',
            'password_confirmation' => 'nova-senha-123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
