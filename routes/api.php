<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\TriviaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\UserProgressController;

// ✅ New Route for grade_level polling
Route::get('/user/{id}/grade-level', [UserController::class, 'getGradeLevel']);

Route::get('/user/progress/keys', [UserProgressController::class, 'getUserKeys']);
Route::post('/user/progress/keys', [UserProgressController::class, 'updateKeys']);

Route::post('/user/progress/increment-answers', [UserProgressController::class, 'incrementAnswers']);
Route::post('/user/progress/update-level', [UserProgressController::class, 'updateLevel']);

Route::post('/user/{id}/change-password', [UserController::class, 'changePassword']);

Route::get('/user/{id}/profile', [UserController::class, 'profile']);
Route::post('/user/{id}/update', [UserController::class, 'update']);
Route::get('/user/{id}/profile', [UserController::class, 'profile']);


Route::get('/user/progress/level/{userId}', [UserProgressController::class, 'getUserLevel']);

Route::get('/leaderboard', [UserProgressController::class, 'getLeaderboard']);

Route::get('/user/progress/coins', [UserProgressController::class, 'getUserCoins']);
Route::post('/user/progress/coins', [UserProgressController::class, 'updateCoins']);

Route::get('/trivia/random', [TriviaController::class, 'getRandomQuestion']);

Route::post('/login', [LoginController::class, 'login']);

Route::post('/register', [RegisterController::class, 'register']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
