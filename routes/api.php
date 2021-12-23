<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\SupportTeamController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login'])->name('login');

     
Route::middleware('auth:sanctum')->group( function () {
    Route::get('/questions', [QuestionController::class, 'index']);
    Route::get('/single/question/{id}', [QuestionController::class, 'show']);
    Route::post('ask/question', [QuestionController::class, 'store']);
    Route::post('reply', [QuestionController::class, 'reply']);
});

Route::middleware(['auth:sanctum', 'isSupportTeam'])->prefix('support')->group( function () {
    Route::get('/all-questions', [SupportTeamController::class, 'index']);
    Route::get('/single/question/{id}', [SupportTeamController::class, 'singleQuestion']);
    Route::post('reply', [SupportTeamController::class, 'reply']);
    Route::post('change-question-status', [SupportTeamController::class, 'changeStatus']);
    Route::post('customer-questions', [SupportTeamController::class, 'customerQuestions']);
    
});