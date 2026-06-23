<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumPost;
use App\Models\Tag;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Support\Str;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $budi   = User::where('email', 'budi@example.com')->first();
        $ani    = User::where('email', 'ani@example.com')->first();
        $citra  = User::where('email', 'citra@example.com')->first();
        $lionel = User::where('email', 'lionel@structify.app')->first();

        $linkedList  = Topic::where('slug', 'linked-list')->first();
        $bubbleSort  = Topic::where('slug', 'bubble-sort')->first();
        $stack       = Topic::where('slug', 'stack')->first();

        if (!$budi || !$linkedList) return;

        // Post 1
        $post1 = ForumPost::create([
            'user_id'  => $budi->id,
            'topic_id' => $linkedList->id,
            'title'    => 'Bingung bedanya Singly vs Doubly Linked List',
            'body'     => "Halo semua, saya sedang belajar Linked List tapi masih bingung kapan pakai Singly dan kapan pakai Doubly. Ada yang bisa jelasin perbedaan praktisnya?\n\nSudah baca materinya tapi masih kurang paham use case-nya.",
            'upvotes'  => 5,
        ]);
        $this->attachTags($post1, ['linked-list', 'struktur-data']);

        // Reply post 1
        $reply1 = ForumPost::create([
            'user_id'   => $ani->id,
            'topic_id'  => $linkedList->id,
            'parent_id' => $post1->id,
            'title'     => null,
            'body'      => "Singly Linked List cukup untuk traversal satu arah (head ke tail). Doubly Linked List dipakai kalau kamu butuh traversal dua arah, misalnya untuk implementasi browser history (back & forward).",
            'upvotes'   => 3,
        ]);

        // Nested reply
        ForumPost::create([
            'user_id'   => $lionel->id,
            'topic_id'  => $linkedList->id,
            'parent_id' => $reply1->id,
            'title'     => null,
            'body'      => "Tambahan: Doubly Linked List juga lebih efisien untuk delete node karena kita bisa akses prev node langsung tanpa traversal dari head.",
            'upvotes'   => 2,
        ]);

        ForumPost::create([
            'user_id'   => $citra->id,
            'topic_id'  => $linkedList->id,
            'parent_id' => $post1->id,
            'title'     => null,
            'body'      => "Kalau cuma butuh stack atau queue, Singly sudah cukup. Doubly lebih cocok untuk LRU Cache implementation.",
            'upvotes'   => 1,
        ]);

        // Post 2
        $post2 = ForumPost::create([
            'user_id'  => $ani->id,
            'topic_id' => $bubbleSort->id,
            'title'    => 'Kenapa Bubble Sort dibilang algoritma yang buruk?',
            'body'     => "Saya baca di beberapa referensi katanya Bubble Sort itu algoritma sorting yang buruk. Tapi kenapa masih diajarkan? Apa ada use case di mana Bubble Sort lebih baik dari yang lain?",
            'upvotes'  => 8,
        ]);
        $this->attachTags($post2, ['sorting', 'algoritma', 'bubble-sort']);

        ForumPost::create([
            'user_id'   => $lionel->id,
            'topic_id'  => $bubbleSort->id,
            'parent_id' => $post2->id,
            'title'     => null,
            'body'      => "Bubble Sort diajarkan karena paling mudah dipahami sebagai pengenalan konsep sorting. Untuk data kecil (< 10 elemen) atau data yang hampir terurut, Bubble Sort bisa cukup efisien karena overhead-nya kecil.",
            'upvotes'   => 4,
        ]);

        // Post 3
        $post3 = ForumPost::create([
            'user_id'  => $citra->id,
            'topic_id' => $stack->id,
            'title'    => 'Implementasi Stack untuk validasi kurung buka-tutup',
            'body'     => "Halo! Saya sedang latihan interview dan menemukan soal validasi kurung buka-tutup menggunakan Stack. Ini kode Python saya:\n\n```python\ndef is_valid(s):\n    stack = []\n    mapping = {')': '(', '}': '{', ']': '['}\n    for char in s:\n        if char in mapping:\n            top = stack.pop() if stack else '#'\n            if mapping[char] != top:\n                return False\n        else:\n            stack.append(char)\n    return not stack\n```\n\nAda yang mau review? Sudah benar atau ada yang perlu diperbaiki?",
            'upvotes'  => 6,
        ]);
        $this->attachTags($post3, ['stack', 'python', 'interview']);

        ForumPost::create([
            'user_id'   => $budi->id,
            'topic_id'  => $stack->id,
            'parent_id' => $post3->id,
            'title'     => null,
            'body'      => "Kodenya sudah benar! Logikanya tepat. Satu saran: tambahkan early return kalau panjang string ganjil, karena kurung valid pasti selalu panjangnya genap.",
            'upvotes'   => 2,
        ]);
    }

    private function attachTags(ForumPost $post, array $tagNames): void
    {
        $tagIds = collect($tagNames)->map(function ($name) {
            return Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'slug' => Str::slug($name)]
            )->id;
        });
        $post->tags()->sync($tagIds);
    }
}