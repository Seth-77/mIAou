<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AskController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Settings\InstructionsController;

Route::middleware(['auth'])->group(function () {
    Route::get('/ask', [AskController::class, 'index'])->name('ask.index');
    Route::post('/ask', [AskController::class, 'ask'])->name('ask.post');

    Route::get('/chat', [ConversationController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ConversationController::class, 'show'])->name('chat.show');
    Route::post('/chat', [ConversationController::class, 'store'])->name('chat.store');
    Route::post('/chat/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::patch('/chat/{conversation}/model', [ConversationController::class, 'updateModel'])->name('chat.model');
    Route::delete('/chat/{conversation}', [ConversationController::class, 'destroy'])->name('chat.destroy');
    Route::get('settings/instructions', [InstructionsController::class, 'edit'])->name('instructions.edit');
    Route::patch('settings/instructions', [InstructionsController::class, 'update'])->name('instructions.update');
});

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__ . '/settings.php';
