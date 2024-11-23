<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/fetch', [PostController::class, 'fetchPosts'])->name('posts.fetch'); 
    Route::get('posts/{post}', [PostController::class, 'edit'])->name('posts.edit'); 
    Route::post('posts', [PostController::class, 'store'])->name('posts.store'); 
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy'); 
    Route::get('blogs', [PostController::class, 'blogs'])->name('blogs.blogs');
});

Route::prefix('api')->group(function () {
    Route::middleware(['csrf.exempt'])->group(function () {
        Route::post('login-api', [AuthController::class, 'login']);
    });    Route::get('/blogs-list', [PostController::class, 'blogsList']); 
});

require __DIR__.'/auth.php';
