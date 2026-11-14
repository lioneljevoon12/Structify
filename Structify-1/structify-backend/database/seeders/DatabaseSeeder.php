<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TrackSeeder::class,
            TopicSeeder::class,
            QuestionSeeder::class,
        ]);

        // UserSeeder berisi akun demo dengan password yang diketahui publik
        // (termasuk admin), jadi hanya dijalankan di local/testing.
        // Untuk production pakai: php artisan db:seed --class=AdminSeeder
        if (app()->environment(['local', 'testing'])) {
            $this->call([
                UserSeeder::class,
                ForumSeeder::class,
            ]);
        }
    }
}
