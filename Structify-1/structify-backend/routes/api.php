<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Admin\AdminQuestionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForumPostController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::get('/tracks',                 [TrackController::class, 'index']);
Route::get('/tracks/{slug}',          [TrackController::class, 'show']);
Route::get('/tracks/{slug}/topics',   [TopicController::class, 'byTrack']);
Route::get('/topics/{slug}',          [TopicController::class, 'show']);

Route::get('/forum',                    [ForumPostController::class, 'index']);
Route::get('/forum/{id}',              [ForumPostController::class, 'show']);
Route::get('/topics/{id}/forum',       [ForumPostController::class, 'byTopic']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    Route::get('/me/progress',              [UserProgressController::class, 'index']);
    Route::patch('/topics/{id}/progress',         [UserProgressController::class, 'update']);
    Route::post('/topics/{id}/visited',           [UserProgressController::class, 'markVisited']);

    Route::get('/topics/{id}/questions', [QuizController::class, 'getQuestions']);
    Route::post('/quiz/submit',          [QuizController::class, 'submit']);
    Route::get('/me/quiz-history',       [QuizController::class, 'history']);
    Route::get('/me/quiz-history/{id}',  [QuizController::class, 'detail']);

    Route::get('/profile',           [ProfileController::class, 'show']);
    Route::post('/profile',          [ProfileController::class, 'update']);
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar']);

    // Forum
    Route::post('/forum',              [ForumPostController::class, 'store']);
    Route::post('/forum/{id}/reply',   [ForumPostController::class, 'reply']);
    Route::post('/forum/{id}/upvote',  [ForumPostController::class, 'upvote']);
    Route::delete('/forum/{id}',       [ForumPostController::class, 'destroy']);

     Route::middleware('role.admin')->prefix('admin')->group(function () {
        Route::get('questions',        [AdminQuestionController::class, 'index']);
        Route::post('questions',       [AdminQuestionController::class, 'store']);
        Route::put('questions/{id}',   [AdminQuestionController::class, 'update']);
        Route::delete('questions/{id}',[AdminQuestionController::class, 'destroy']);

        Route::get('users',                  [AdminUserController::class, 'index']);
        Route::get('users/{id}',             [AdminUserController::class, 'show']);
        Route::patch('users/{id}/ban',       [AdminUserController::class, 'ban']);
        Route::patch('users/{id}/promote',   [AdminUserController::class, 'promote']);

        Route::get('analytics', [AdminAnalyticsController::class, 'index']);
    });

});