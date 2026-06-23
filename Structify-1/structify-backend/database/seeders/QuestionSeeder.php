<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Topic;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            // Array
            'array' => [
                [
                    'question_text' => 'Apa kompleksitas waktu akses elemen array berdasarkan index?',
                    'option_a'      => 'O(n)',
                    'option_b'      => 'O(log n)',
                    'option_c'      => 'O(1)',
                    'option_d'      => 'O(n²)',
                    'correct_answer'=> 'C',
                    'timer_seconds' => 30,
                ],
                [
                    'question_text' => 'Manakah operasi yang paling lambat pada array?',
                    'option_a'      => 'Akses elemen by index',
                    'option_b'      => 'Insert di tengah array',
                    'option_c'      => 'Update elemen by index',
                    'option_d'      => 'Baca panjang array',
                    'correct_answer'=> 'B',
                    'timer_seconds' => 30,
                ],
                [
                    'question_text' => 'Array berbeda dari Linked List karena...',
                    'option_a'      => 'Array bisa menyimpan tipe data berbeda',
                    'option_b'      => 'Array disimpan di memori yang berurutan (contiguous)',
                    'option_c'      => 'Array tidak bisa diiterasi',
                    'option_d'      => 'Array ukurannya dinamis',
                    'correct_answer'=> 'B',
                    'timer_seconds' => 30,
                ],
            ],
            // Linked List
            'linked-list' => [
                [
                    'question_text' => 'Apa kompleksitas insert di awal Singly Linked List?',
                    'option_a'      => 'O(n)',
                    'option_b'      => 'O(n²)',
                    'option_c'      => 'O(log n)',
                    'option_d'      => 'O(1)',
                    'correct_answer'=> 'D',
                    'timer_seconds' => 30,
                ],
                [
                    'question_text' => 'Node terakhir Singly Linked List menunjuk ke?',
                    'option_a'      => 'Node pertama',
                    'option_b'      => 'Dirinya sendiri',
                    'option_c'      => 'NULL',
                    'option_d'      => 'Node tengah',
                    'correct_answer'=> 'C',
                    'timer_seconds' => 20,
                ],
            ],
            // Stack
            'stack' => [
                [
                    'question_text' => 'Stack menggunakan prinsip?',
                    'option_a'      => 'FIFO (First In First Out)',
                    'option_b'      => 'LIFO (Last In First Out)',
                    'option_c'      => 'Random Access',
                    'option_d'      => 'Priority Based',
                    'correct_answer'=> 'B',
                    'timer_seconds' => 20,
                ],
                [
                    'question_text' => 'Operasi menambah elemen ke stack disebut?',
                    'option_a'      => 'Enqueue',
                    'option_b'      => 'Insert',
                    'option_c'      => 'Push',
                    'option_d'      => 'Add',
                    'correct_answer'=> 'C',
                    'timer_seconds' => 20,
                ],
            ],
            // Queue
            'queue' => [
                [
                    'question_text' => 'Queue menggunakan prinsip?',
                    'option_a'      => 'LIFO (Last In First Out)',
                    'option_b'      => 'FIFO (First In First Out)',
                    'option_c'      => 'Random Access',
                    'option_d'      => 'Priority Based',
                    'correct_answer'=> 'B',
                    'timer_seconds' => 20,
                ],
                [
                    'question_text' => 'Operasi menghapus elemen dari Queue disebut?',
                    'option_a'      => 'Pop',
                    'option_b'      => 'Remove',
                    'option_c'      => 'Delete',
                    'option_d'      => 'Dequeue',
                    'correct_answer'=> 'D',
                    'timer_seconds' => 20,
                ],
            ],
            // Bubble Sort
            'bubble-sort' => [
                [
                    'question_text' => 'Apa kompleksitas waktu worst case Bubble Sort?',
                    'option_a'      => 'O(n)',
                    'option_b'      => 'O(n log n)',
                    'option_c'      => 'O(n²)',
                    'option_d'      => 'O(log n)',
                    'correct_answer'=> 'C',
                    'timer_seconds' => 30,
                ],
                [
                    'question_text' => 'Bubble Sort disebut "bubble" karena?',
                    'option_a'      => 'Elemen terkecil naik ke atas seperti gelembung',
                    'option_b'      => 'Elemen terbesar naik ke akhir array seperti gelembung',
                    'option_c'      => 'Algoritmanya berbentuk lingkaran',
                    'option_d'      => 'Ditemukan oleh ilmuwan bernama Bubble',
                    'correct_answer'=> 'B',
                    'timer_seconds' => 30,
                ],
            ],
            // Binary Search
            'binary-search' => [
                [
                    'question_text' => 'Syarat utama untuk menggunakan Binary Search adalah?',
                    'option_a'      => 'Array harus berisi angka saja',
                    'option_b'      => 'Array harus sudah terurut',
                    'option_c'      => 'Array tidak boleh kosong',
                    'option_d'      => 'Array harus berukuran genap',
                    'correct_answer'=> 'B',
                    'timer_seconds' => 30,
                ],
                [
                    'question_text' => 'Kompleksitas waktu Binary Search adalah?',
                    'option_a'      => 'O(n)',
                    'option_b'      => 'O(n²)',
                    'option_c'      => 'O(1)',
                    'option_d'      => 'O(log n)',
                    'correct_answer'=> 'D',
                    'timer_seconds' => 25,
                ],
            ],
            // OOP - Class & Object
            'class-object' => [
                [
                    'question_text' => 'Apa perbedaan Class dan Object?',
                    'option_a'      => 'Class adalah instance dari Object',
                    'option_b'      => 'Object adalah blueprint, Class adalah instance-nya',
                    'option_c'      => 'Class adalah blueprint, Object adalah instance-nya',
                    'option_d'      => 'Keduanya sama saja',
                    'correct_answer'=> 'C',
                    'timer_seconds' => 30,
                ],
            ],
        ];

        foreach ($questions as $topicSlug => $topicQuestions) {
            $topic = Topic::where('slug', $topicSlug)->first();
            if (!$topic) continue;

            foreach ($topicQuestions as $q) {
                Question::firstOrCreate(
                    [
                        'topic_id'      => $topic->id,
                        'question_text' => $q['question_text'],
                    ],
                    array_merge($q, ['topic_id' => $topic->id])
                );
            }
        }
    }
}