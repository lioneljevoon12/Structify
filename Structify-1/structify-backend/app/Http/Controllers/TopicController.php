<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Track;
use Illuminate\Http\Request;
use App\Http\Resources\TopicResource;
use app\Http\Resources\TrackResource;

class TopicController extends Controller
{
public function byTrack($slug)
{
    $track = Track::where('slug', $slug)->firstOrFail();
    $topics = Topic::where('track_id', $track->id)
        ->orderBy('display_order')
        ->get();

    return response()->json([
        'track' => new TrackResource($track),
        'data'  => TopicResource::collection($topics),
    ]);
}

public function show($slug)
{
    $topic = Topic::where('slug', $slug)
        ->with('track')
        ->firstOrFail();
    return new TopicResource($topic);
}
}