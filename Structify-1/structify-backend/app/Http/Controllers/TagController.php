<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('forumPosts')
            ->orderByDesc('forum_posts_count')
            ->get();

        return response()->json(['data' => $tags]);
    }

    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->forumPosts()
            ->whereNull('parent_id')
            ->with([
                'user:id,name,username,avatar',
                'topic:id,title,slug',
                'tags:id,name,slug',
            ])
            ->withCount('replies')
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'tag'  => $tag,
            'data' => $posts,
        ]);
    }
}