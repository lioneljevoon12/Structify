<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Admin\AdminQuestionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::get('/tracks',                 [TrackController::class, 'index']);
Route::get('/tracks/{slug}',          [TrackController::class, 'show']);
Route::get('/tracks/{slug}/topics',   [TopicController::class, 'byTrack']);
Route::get('/topics/{slug}',          [TopicController::class, 'show']);

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

     Route::middleware('role.admin')->prefix('admin')->group(function () {
        Route::get('questions',        [AdminQuestionController::class, 'index']);
        Route::post('questions',       [AdminQuestionController::class, 'store']);
        Route::put('questions/{id}',   [AdminQuestionController::class, 'update']);
        Route::delete('questions/{id}',[AdminQuestionController::class, 'destroy']);
    });

});