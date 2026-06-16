<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\Tag;
use App\Http\Requests\StoreForumPostRequest;
use App\Http\Requests\StoreReplyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumPostController extends Controller
{
    public function index(Request $request)
    {
        $posts = ForumPost::whereNull('parent_id')
            ->with([
                'user:id,name,username,avatar',
                'topic:id,title,slug',
                'tags:id,name,slug',
            ])
            ->withCount('replies')
            ->when($request->topic_id, fn($q) =>
                $q->where('topic_id', $request->topic_id)
            )
            ->when($request->tag, fn($q) =>
                $q->whereHas('tags', fn($q) =>
                    $q->where('slug', $request->tag)
                )
            )
            ->when($request->search, fn($q) =>
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('body', 'like', "%{$request->search}%")
            )
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($posts);
    }

    public function store(StoreForumPostRequest $request)
    {
        $post = ForumPost::create([
            'user_id'  => $request->user()->id,
            'topic_id' => $request->topic_id,
            'title'    => $request->title,
            'body'     => $request->body,
        ]);

        // Handle tags
        if ($request->has('tags')) {
            $tagIds = collect($request->tags)->map(function ($tagName) {
                return Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName, 'slug' => Str::slug($tagName)]
                )->id;
            });
            $post->tags()->sync($tagIds);
        }

        $post->load([
            'user:id,name,username,avatar',
            'topic:id,title,slug',
            'tags:id,name,slug',
        ]);

        return response()->json([
            'message' => 'Post berhasil dibuat',
            'data'    => $post,
        ], 201);
    }

    public function show($id)
    {
        $post = ForumPost::with([
            'user:id,name,username,avatar',
            'topic:id,title,slug',
            'tags:id,name,slug',
            'replies.user:id,name,username,avatar',
            'replies.nestedReplies.user:id,name,username,avatar',
            'replies.nestedReplies.nestedReplies.user:id,name,username,avatar',
        ])
        ->withCount('replies')
        ->findOrFail($id);

        return response()->json(['data' => $post]);
    }

    public function reply(StoreReplyRequest $request, $id)
    {
    $post = ForumPost::findOrFail($id);

    // Cari root post — kalau reply ke reply, parent_id tetap ke post aslinya
    // tapi kita track replied_to_id untuk context
    $rootPost = $post->parent_id
        ? ForumPost::findOrFail($post->parent_id)
        : $post;

    $reply = ForumPost::create([
        'user_id'   => $request->user()->id,
        'topic_id'  => $rootPost->topic_id,
        'parent_id' => $post->id,  // langsung reply ke post/reply yang dituju
        'title'     => null,
        'body'      => $request->body,
    ]);

    $reply->load('user:id,name,username,avatar');

    return response()->json([
        'message'    => 'Reply berhasil ditambahkan',
        'data'       => $reply,
        'replied_to' => $post->id,
    ], 201);
}

    public function upvote(Request $request, $id)
    {
        $post = ForumPost::findOrFail($id);
        $post->increment('upvotes');

        return response()->json([
            'message' => 'Upvote berhasil',
            'upvotes' => $post->upvotes,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $post = ForumPost::findOrFail($id);

        // Hanya pemilik post atau admin yang bisa hapus
        if ($post->user_id !== $request->user()->id &&
            !in_array($request->user()->role, ['admin', 'developer'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post berhasil dihapus']);
    }

    public function byTopic(Request $request, $topicId)
    {
        $posts = ForumPost::whereNull('parent_id')
            ->where('topic_id', $topicId)
            ->with([
                'user:id,name,username,avatar',
                'tags:id,name,slug',
            ])
            ->withCount('replies')
            ->orderByDesc('upvotes')
            ->paginate(15);

        return response()->json($posts);
    }
}