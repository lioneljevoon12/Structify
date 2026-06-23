<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin Structify',
                'email'    => 'admin@structify.app',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'username' => 'admin_structify',
                'bio'      => 'Administrator Structify',
            ],
            [
                'name'     => 'Lionel Jevon',
                'email'    => 'lionel@structify.app',
                'password' => Hash::make('password123'),
                'role'     => 'developer',
                'username' => 'lionelj',
                'bio'      => 'Developer & founder Structify',
            ],
            [
                'name'     => 'Budi Santoso',
                'email'    => 'budi@example.com',
                'password' => Hash::make('password123'),
                'role'     => 'user',
                'username' => 'budi_s',
                'bio'      => 'Mahasiswa Informatika semester 3',
            ],
            [
                'name'     => 'Ani Rahayu',
                'email'    => 'ani@example.com',
                'password' => Hash::make('password123'),
                'role'     => 'user',
                'username' => 'ani_r',
                'bio'      => 'Suka belajar algoritma dan struktur data',
            ],
            [
                'name'     => 'Citra Dewi',
                'email'    => 'citra@example.com',
                'password' => Hash::make('password123'),
                'role'     => 'user',
                'username' => 'citra_d',
                'bio'      => 'Fresh graduate yang lagi persiapan interview',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}