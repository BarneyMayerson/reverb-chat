<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('root');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::post('/', [ReactionController::class, 'store'])->name('reactions.store');

Route::controller(UserController::class)->prefix('users')->as('users.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{user}', 'show')->name('show');
});

Route::controller(ChatController::class)
    ->middleware('auth')
    ->prefix('chats')
    ->as('chats.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{chat}', 'show')->name('show');
        Route::get('/{user}/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::post('/{chat}/message', 'message')->name('message');
    });
