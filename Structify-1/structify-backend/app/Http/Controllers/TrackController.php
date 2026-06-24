<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use App\Http\Resources\TrackResource;

class TrackController extends Controller
{
    public function index()
    {
        $tracks = Track::withCount('topics')->get();
        return TrackResource::collection($tracks);
    }

    public function show($slug)
    {
        $track = Track::where('slug', $slug)
            ->withCount('topics')
            ->firstOrFail();
        return new TrackResource($track);
    }
}