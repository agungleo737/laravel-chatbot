<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController; 
use App\Http\Controllers\SessionController;

//route auth
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request){
   return $request->user();
});

//route chatbot
Route::post('/chat', [ChatbotController::class, 'ask']);

Route::get('/sessions', [SessionController::class, 'index']);
Route::post('/sessions', [SessionController::class, 'store']);
Route::get('/sessions/{id}', [SessionController::class, 'show']);
Route::delete('/sessions/{id}', [SessionController::class, 'destroy']);
