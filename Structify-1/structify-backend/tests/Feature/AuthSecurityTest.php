<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_banned_user_token_is_rejected_and_revoked(): void
    {
        $user  = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->update(['is_banned' => true]);

        $this->withToken($token)->getJson('/api/me')->assertStatus(403);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_banning_revokes_all_tokens(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create();
        $target->createToken('a');
        $target->createToken('b');

        $this->withToken($admin->createToken('admin')->plainTextToken)
            ->patchJson("/api/admin/users/{$target->id}/ban")
            ->assertOk()
            ->assertJson(['is_banned' => true]);

        $this->assertSame(0, $target->tokens()->count());
    }

    public function test_forgot_password_does_not_reveal_unknown_emails(): void
    {
        Notification::fake();
        User::factory()->create(['email' => 'known@example.com']);

        $known   = $this->postJson('/api/forgot-password', ['email' => 'known@example.com']);
        $unknown = $this->postJson('/api/forgot-password', ['email' => 'nobody@example.com']);

        $known->assertOk();
        $unknown->assertOk();
        $this->assertSame($known->json('message'), $unknown->json('message'));
    }

    public function test_reset_link_uses_configured_frontend_url(): void
    {
        config(['app.frontend_url' => 'https://app.structify.test']);
        Notification::fake();
        $user = User::factory()->create();

        $this->postJson('/api/forgot-password', ['email' => $user->email])->assertOk();

        Notification::assertSentTo($user, \App\Notifications\ResetPasswordNotification::class,
            function ($notification) use ($user) {
                $url = $notification->toMail($user)->actionUrl;
                return str_starts_with($url, 'https://app.structify.test/reset-password?');
            });
    }

    public function test_password_reset_revokes_existing_tokens(): void
    {
        $user = User::factory()->create();
        $user->createToken('old');
        $resetToken = Password::createToken($user);

        $this->postJson('/api/reset-password', [
            'email'                 => $user->email,
            'token'                 => $resetToken,
            'password'              => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertOk();

        $this->assertSame(0, $user->tokens()->count());
        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_login_is_rate_limited(): void
    {
        User::factory()->create(['email' => 'target@example.com']);

        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/login', ['email' => 'target@example.com', 'password' => 'wrong'])
                ->assertStatus(422);
        }

        $this->postJson('/api/login', ['email' => 'target@example.com', 'password' => 'wrong'])
            ->assertStatus(429);
    }
}
