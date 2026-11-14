<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminSeeder extends Seeder
{
    /**
     * Buat akun admin dari ADMIN_EMAIL dan ADMIN_PASSWORD di .env.
     * Jalankan: php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (!$email || !$password || strlen($password) < 12) {
            throw new RuntimeException(
                'Set ADMIN_EMAIL dan ADMIN_PASSWORD (minimal 12 karakter) di .env sebelum menjalankan AdminSeeder.'
            );
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->forceFill([
            'name'              => $user->name ?: 'Admin Structify',
            'password'          => Hash::make($password),
            'role'              => 'admin',
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();
    }
}
