<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RepostController;

Route::get('/', function (Request $request) {
    // If the incoming link contains LMS-style query params, send guests to login directly
    if ($request->query('classId') || $request->query('assignmentId') || $request->query('submissionId')) {
        return redirect()->route('login');
    }

    // If user already authenticated, send to main tweets feed
    if (auth()->check()) {
        return redirect()->route('tweets.index');
    }

    // Default welcome page for guests without LMS params
    return view('welcome');
});

// Authentication
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');

// Tweets
Route::middleware('auth')->group(function () {
    Route::get('/tweets', [TweetController::class, 'index'])->name('tweets.index');
    Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');
    Route::put('/tweets/{tweet}', [TweetController::class, 'update'])->name('tweets.update');
    Route::delete('/tweets/{tweet}', [TweetController::class, 'destroy'])->name('tweets.destroy');
    Route::post('/tweets/{tweet}/like', [LikeController::class, 'toggle'])->name('tweets.like');
});

// Messages
Route::middleware('auth')->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
});

// Stories (My Day)
Route::middleware('auth')->group(function () {
    Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::delete('/stories/{story}', [StoryController::class, 'destroy'])->name('stories.destroy');
});

// Profiles
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Follow
Route::middleware('auth')->group(function () {
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');
});

// Browse Users
Route::middleware('auth')->group(function () {
    Route::get('/discover', [UserController::class, 'index'])->name('users.index');
});

// Comments
Route::middleware('auth')->group(function () {
    Route::post('/tweets/{tweet}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Reposts
Route::middleware('auth')->group(function () {
    Route::post('/tweets/{tweet}/repost', [RepostController::class, 'store'])->name('tweets.repost');
});
