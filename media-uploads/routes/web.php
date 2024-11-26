<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SettingsController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/media', [MediaController::class, 'showMedia'])->middleware('auth:sanctum')->name('media');
Route::get('/my-media', [MediaController::class, 'showYourMedia'])->middleware('auth:sanctum')->name('my-media');
Route::get('/settings', [SettingsController::class, 'showSettings'])->middleware('auth:sanctum')->name('settings');

Route::post('/regenerate-api-key', [AuthController::class, 'regenerateApiKey'])->name('regenerate.api.key');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth:sanctum')->name('dashboard');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

