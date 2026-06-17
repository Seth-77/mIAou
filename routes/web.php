<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AskController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Settings\InstructionsController;
use App\Http\Controllers\AskStreamController;
use App\Http\Controllers\TagController;

Route::middleware(['auth'])->group(function () {
    Route::get('/ask', [AskController::class, 'index'])->name('ask.index');
    Route::post('/ask', [AskController::class, 'ask'])->name('ask.post');
    Route::get('/ask-stream', [AskStreamController::class, 'index'])->name('stream.index');
    Route::post('/ask-stream', [AskStreamController::class, 'stream'])->name('stream.post');
    Route::post('/chat/{conversation}/messages/stream', [MessageController::class, 'stream'])->name('messages.stream');

    Route::get('/chat', [ConversationController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ConversationController::class, 'show'])->name('chat.show');
    Route::post('/chat', [ConversationController::class, 'store'])->name('chat.store');
    Route::post('/chat/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::patch('/chat/{conversation}/model', [ConversationController::class, 'updateModel'])->name('chat.model');
    Route::delete('/chat/{conversation}', [ConversationController::class, 'destroy'])->name('chat.destroy');
    Route::get('settings/instructions', [InstructionsController::class, 'edit'])->name('instructions.edit');
    Route::patch('settings/instructions', [InstructionsController::class, 'update'])->name('instructions.update');
    Route::post('/chat/{conversation}/tags', [TagController::class, 'attach'])->name('tags.attach');
    Route::delete('/chat/{conversation}/tags/{tag}', [TagController::class, 'detach'])->name('tags.detach');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
});

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', fn() => redirect('/chat'))->name('dashboard');
});

require __DIR__ . '/settings.php';
