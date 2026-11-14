<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    private function fakeGoogleUser(string $id, string $email, bool $verified = true): void
    {
        $googleUser = (new SocialiteUser())
            ->setRaw(['email_verified' => $verified])
            ->map(['id' => $id, 'email' => $email, 'name' => 'Victim Name']);

        $provider = Mockery::mock();
        $provider->shouldReceive('stateless')->andReturnSelf();
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    private function codeFromRedirect($response): string
    {
        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);
        $this->assertArrayHasKey('code', $query);

        return $query['code'];
    }

    public function test_callback_redirects_with_one_time_code_instead_of_token(): void
    {
        config(['app.frontend_url' => 'https://app.structify.test']);
        $this->fakeGoogleUser('g-1', 'new@example.com');

        $response = $this->get('/api/auth/google/callback');

        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringStartsWith('https://app.structify.test/auth/callback?code=', $location);
        $this->assertStringNotContainsString('token=', $location);

        $code = $this->codeFromRedirect($response);

        $this->postJson('/api/auth/google/exchange', ['code' => $code])
            ->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'email']]);

        // Kode hanya bisa dipakai sekali
        $this->postJson('/api/auth/google/exchange', ['code' => $code])->assertStatus(401);
    }

    public function test_linking_unverified_manual_account_wipes_password_and_sessions(): void
    {
        // Akun yang didaftarkan orang lain memakai email korban (belum verifikasi email)
        $squatter = User::factory()->unverified()->create(['email' => 'victim@example.com']);
        $squatter->createToken('attacker');

        $this->fakeGoogleUser('g-victim', 'victim@example.com');
        $this->get('/api/auth/google/callback')->assertRedirect();

        $squatter->refresh();
        $this->assertSame('g-victim', $squatter->google_id);
        $this->assertNull($squatter->password);
        $this->assertNotNull($squatter->email_verified_at);
        $this->assertSame(0, $squatter->tokens()->count());
    }

    public function test_verified_manual_account_keeps_password_when_linking(): void
    {
        $user = User::factory()->create(['email' => 'real@example.com']);
        $hash = $user->password;

        $this->fakeGoogleUser('g-real', 'real@example.com');
        $this->get('/api/auth/google/callback')->assertRedirect();

        $this->assertSame($hash, $user->fresh()->password);
        $this->assertSame('g-real', $user->fresh()->google_id);
    }

    public function test_unverified_google_email_is_rejected(): void
    {
        $this->fakeGoogleUser('g-2', 'unverified@example.com', verified: false);

        $location = $this->get('/api/auth/google/callback')->headers->get('Location');

        $this->assertStringContainsString('error=email_unverified', $location);
        $this->assertDatabaseMissing('users', ['email' => 'unverified@example.com']);
    }
}
