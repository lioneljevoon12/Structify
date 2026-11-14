<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Http\Requests\GoogleMobileLoginRequest;
use Google\Client as GoogleClient;

class GoogleAuthController extends Controller
{
    /** Masa berlaku kode sekali pakai dari redirect web, dalam detik. */
    private const EXCHANGE_CODE_TTL = 60;

    public function redirect()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function callback(Request $request)
    {
        $frontendUrl = rtrim(config('app.frontend_url'), '/');

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect("{$frontendUrl}/auth/callback?error=google_failed");
        }

        $raw = $googleUser->getRaw();
        $emailVerified = filter_var(
            $raw['email_verified'] ?? $raw['verified_email'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );

        if (!$googleUser->getEmail() || !$emailVerified) {
            return redirect("{$frontendUrl}/auth/callback?error=email_unverified");
        }

        $user = $this->resolveUser(
            $googleUser->getId(),
            $googleUser->getEmail(),
            $googleUser->getName() ?: 'User'
        );

        if (!$user) {
            return redirect("{$frontendUrl}/auth/callback?error=account_conflict");
        }

        if ($user->is_banned) {
            return redirect("{$frontendUrl}/auth/callback?error=banned");
        }

        // Token tidak dikirim di URL (bisa bocor ke history/log). Frontend menukar
        // kode sekali pakai ini lewat POST /api/auth/google/exchange.
        $code = Str::random(64);
        Cache::put($this->exchangeCacheKey($code), $user->id, self::EXCHANGE_CODE_TTL);

        return redirect("{$frontendUrl}/auth/callback?code={$code}");
    }

    public function exchange(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:64',
        ]);

        $userId = Cache::pull($this->exchangeCacheKey($request->code));
        $user   = $userId ? User::find($userId) : null;

        if (!$user) {
            return response()->json([
                'message' => 'Kode login tidak valid atau sudah kedaluwarsa.'
            ], 401);
        }

        if ($user->is_banned) {
            return response()->json([
                'message' => 'Akun kamu telah dibanned. Hubungi admin.'
            ], 403);
        }

        return $this->tokenResponse($user);
    }

    public function mobileLogin(GoogleMobileLoginRequest $request)
    {
        $client = new GoogleClient(['client_id' => config('services.google.client_id')]);

        try {
            $payload = $client->verifyIdToken($request->id_token);
        } catch (\Exception $e) {
            $payload = false;
        }

        if (!$payload) {
            return response()->json([
                'message' => 'Token Google tidak valid.'
            ], 401);
        }

        $emailVerified = filter_var($payload['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if (empty($payload['email']) || !$emailVerified) {
            return response()->json([
                'message' => 'Email akun Google belum terverifikasi.'
            ], 401);
        }

        $user = $this->resolveUser($payload['sub'], $payload['email'], $payload['name'] ?? 'User');

        if (!$user) {
            return response()->json([
                'message' => 'Email ini sudah terhubung dengan akun Google lain.'
            ], 409);
        }

        if ($user->is_banned) {
            return response()->json([
                'message' => 'Akun kamu telah dibanned. Hubungi admin.'
            ], 403);
        }

        return $this->tokenResponse($user);
    }

    /**
     * Cari atau buat user untuk login Google. Mengembalikan null kalau email
     * sudah terhubung ke Google ID lain.
     */
    private function resolveUser(string $googleId, string $email, string $name): ?User
    {
        $user = User::where('google_id', $googleId)->first()
            ?? User::where('email', $email)->first();

        if (!$user) {
            $user = new User();
            $user->forceFill([
                'name'              => $name,
                'email'             => $email,
                'google_id'         => $googleId,
                'username'          => Str::limit(Str::slug($name), 40, '') . '_' . Str::random(4),
                'password'          => null,
                'email_verified_at' => now(),
            ])->save();

            return $user;
        }

        if ($user->google_id && $user->google_id !== $googleId) {
            return null;
        }

        if (!$user->google_id) {
            $updates = ['google_id' => $googleId];

            // Akun daftar manual yang emailnya belum diverifikasi bisa saja dibuat orang
            // lain memakai email ini. Google sudah membuktikan pemilik email, jadi password
            // lama dan semua sesi dicabut. Pemilik bisa set password lagi via forgot-password.
            if (is_null($user->email_verified_at)) {
                $updates['password'] = null;
                $updates['email_verified_at'] = now();
                $user->tokens()->delete();
            }

            $user->forceFill($updates)->save();
        }

        return $user;
    }

    private function tokenResponse(User $user)
    {
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Google berhasil',
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role,
            ],
            'token' => $token,
        ]);
    }

    private function exchangeCacheKey(string $code): string
    {
        return 'google_login_code:' . hash('sha256', $code);
    }
}
