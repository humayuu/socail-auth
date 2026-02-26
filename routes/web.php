<?php

use App\Http\Controllers\GithubController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LinkedinController;
use App\Http\Controllers\ProfileController;
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

// Google Auth Routes
Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'RedirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'HandleGoogleCallback');
});

// Github Auth Routes
Route::controller(GithubController::class)->group(function () {
    Route::get('auth/github', 'RedirectToGithub')->name('auth.github');
    Route::get('auth/github/callback', 'HandleGithubCallback');
});

require __DIR__ . '/auth.php';
