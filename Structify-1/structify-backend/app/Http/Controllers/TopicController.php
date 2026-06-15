<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Track;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function byTrack($slug)
    {
        $track = Track::where('slug', $slug)->firstOrFail();

        $topics = Topic::where('track_id', $track->id)
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'track'  => $track,
            'data'   => $topics,
        ]);
    }

    public function show($slug)
    {
        $topic = Topic::where('slug', $slug)
            ->with('track')
            ->firstOrFail();

        return response()->json(['data' => $topic]);
    }
}