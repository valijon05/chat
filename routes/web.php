<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('home');
    Route::get('/chat/{userId}', [MessageController::class, 'index'])->name('chat');
    Route::post('/message', [MessageController::class, 'sendMessage'])->name('message.send');
    Route::get('/fetch-messages/{userId}', [MessageController::class, 'fetchMessages']);

});
require __DIR__.'/auth.php';
