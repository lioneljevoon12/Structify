<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use App\Models\Topic;
use Illuminate\Http\Request;

class UserProgressController extends Controller
{
    public function index(Request $request)
    {
        $progress = UserProgress::where('user_id', $request->user()->id)
            ->with('topic.track')
            ->get()
            ->groupBy(fn($p) => $p->topic->track->slug);

        return response()->json(['data' => $progress]);
    }

    public function update(Request $request, $topicId)
    {
        $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $topic = Topic::findOrFail($topicId);

        $progress = UserProgress::updateOrCreate(
            [
                'user_id'  => $request->user()->id,
                'topic_id' => $topic->id,
            ],
            [
                'is_completed'          => $request->is_completed,
                'completion_percentage' => $request->is_completed ? 100 : 0,
                'last_visited_at'       => now(),
            ]
        );

        return response()->json([
            'message' => 'Progress updated',
            'data'    => $progress,
        ]);
    }

    public function markVisited(Request $request, $topicId)
    {
        $topic = Topic::findOrFail($topicId);

        $progress = UserProgress::updateOrCreate(
            [
                'user_id'  => $request->user()->id,
                'topic_id' => $topic->id,
            ],
            [
                'last_visited_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Visited recorded',
            'data'    => $progress,
        ]);
    }
}