<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Track;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            'struktur-data' => [
                'Array', 'Linked List', 'Double Linked List',
                'Circular Linked List', 'Stack', 'Queue',
                'Tree', 'Binary Search Tree', 'AVL Tree',
                'Graph', 'Hash Table', 'Heap',
            ],
            'algoritma' => [
                'Bubble Sort', 'Selection Sort', 'Insertion Sort',
                'Merge Sort', 'Quick Sort', 'Heap Sort',
                'Linear Search', 'Binary Search',
                'Rekursi', 'Dynamic Programming',
                'Greedy', 'Backtracking', 'Divide & Conquer',
            ],
            'matematika-diskrit' => [
                'Logika Proposisi', 'Himpunan & Operasi',
                'Relasi & Fungsi', 'Graf Diskrit', 'Tree Diskrit',
                'Kombinatorik', 'Teori Bilangan', 'Induksi Matematika',
            ],
            'oop' => [
                'Class & Object', 'Constructor & Destructor',
                'Encapsulation', 'Inheritance', 'Polymorphism',
                'Abstraction', 'Interface & Abstract Class',
                'Design Patterns',
            ],
        ];

        foreach ($topics as $trackSlug => $topicList) {
            $track = Track::where('slug', $trackSlug)->first();

            foreach ($topicList as $order => $title) {
                Topic::create([
                    'track_id'      => $track->id,
                    'title'         => $title,
                    'slug'          => str($title)->slug(),
                    'display_order' => $order + 1,
                ]);
            }
        }
    }
}