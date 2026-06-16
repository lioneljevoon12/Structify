<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizAttempt;
use App\Models\Topic;
use App\Models\UserProgress;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        // Total users
        $totalUsers = User::where('role', 'user')->count();

        // Active users (login dalam 30 hari = punya quiz attempt atau progress dalam 30 hari)
        $activeUsers = User::where('role', 'user')
            ->where(function ($q) {
                $q->whereHas('quizAttempts', fn($q) =>
                    $q->where('completed_at', '>=', now()->subDays(30))
                )
                ->orWhereHas('progress', fn($q) =>
                    $q->where('last_visited_at', '>=', now()->subDays(30))
                );
            })
            ->count();

        // Total quiz attempts
        $totalAttempts = QuizAttempt::count();

        // Average score keseluruhan
        $avgScore = QuizAttempt::avg('score');

        // Topik terpopuler berdasarkan jumlah quiz attempts
        $popularTopics = QuizAttempt::select('topic_id', DB::raw('count(*) as attempt_count'))
            ->with('topic:id,title,slug')
            ->groupBy('topic_id')
            ->orderByDesc('attempt_count')
            ->limit(5)
            ->get()
            ->map(fn($a) => [
                'topic'         => $a->topic?->title,
                'slug'          => $a->topic?->slug,
                'attempt_count' => $a->attempt_count,
            ]);

        // Rata-rata skor per topik
        $avgScorePerTopic = QuizAttempt::select(
                'topic_id',
                DB::raw('ROUND(AVG(score), 2) as avg_score'),
                DB::raw('count(*) as total_attempts')
            )
            ->with('topic:id,title')
            ->groupBy('topic_id')
            ->orderByDesc('avg_score')
            ->limit(10)
            ->get()
            ->map(fn($a) => [
                'topic'          => $a->topic?->title,
                'avg_score'      => $a->avg_score,
                'total_attempts' => $a->total_attempts,
            ]);

        // Total completed topics
        $completedTopics = UserProgress::where('is_completed', true)->count();

        // User baru per 7 hari terakhir
        $newUsersThisWeek = User::where('role', 'user')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // Banned users
        $bannedUsers = User::where('is_banned', true)->count();

        return response()->json([
            'data' => [
                'users' => [
                    'total'             => $totalUsers,
                    'active_last_30d'   => $activeUsers,
                    'new_this_week'     => $newUsersThisWeek,
                    'banned'            => $bannedUsers,
                ],
                'quiz' => [
                    'total_attempts'    => $totalAttempts,
                    'avg_score'         => round($avgScore ?? 0, 2),
                    'completed_topics'  => $completedTopics,
                ],
                'popular_topics'        => $popularTopics,
                'avg_score_per_topic'   => $avgScorePerTopic,
            ],
        ]);
    }
}