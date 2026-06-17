<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login Google gagal. Coba lagi.'
            ], 400);
        }

        // Cek user sudah ada berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id kalau belum ada (user yang daftar manual lalu login via Google)
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        } else {
            // Buat user baru
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'username'  => Str::slug($googleUser->getName()) . '_' . Str::random(4),
                'password'  => null,
            ]);
        }

        if ($user->is_banned) {
            return response()->json([
                'message' => 'Akun kamu telah dibanned. Hubungi admin.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Redirect ke frontend dengan token di query param
        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
        return redirect("{$frontendUrl}/auth/callback?token={$token}");
    }
}