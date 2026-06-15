<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Track;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        $tracks = [
            [
                'name'        => 'Struktur Data',
                'slug'        => 'struktur-data',
                'description' => 'Pelajari cara menyimpan dan mengorganisasi data secara efisien.',
                'icon'        => '🗂️',
            ],
            [
                'name'        => 'Algoritma & Pemrograman',
                'slug'        => 'algoritma',
                'description' => 'Pelajari teknik-teknik algoritma dan pemrograman.',
                'icon'        => '⚙️',
            ],
            [
                'name'        => 'Matematika Diskrit',
                'slug'        => 'matematika-diskrit',
                'description' => 'Dasar matematika untuk ilmu komputer.',
                'icon'        => '🔢',
            ],
            [
                'name'        => 'OOP',
                'slug'        => 'oop',
                'description' => 'Konsep Object-Oriented Programming.',
                'icon'        => '🧱',
            ],
        ];

        foreach ($tracks as $track) {
            Track::create($track);
        }
    }
}