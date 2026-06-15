<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function index()
    {
        $tracks = Track::withCount('topics')->get();

        return response()->json(['data' => $tracks]);
    }

    public function show($slug)
    {
        $track = Track::where('slug', $slug)
            ->withCount('topics')
            ->firstOrFail();

        return response()->json(['data' => $track]);
    }
}