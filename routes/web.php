<?php

use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::get('/top-urls', function () {
    return view('dashboard.stats'); 
})->name('top-urls');

Route::get('/my-urls', function () {
    return view('dashboard.urls'); 
})->name('my-urls');

Route::get('/check-url', function () {
    return view('dashboard.check-url'); 
})->name('check-url');

Route::get("{code}", [UrlController::class, "redirectToOriginalUrl"])->name("url.redirect");
Route::post("/short-url", [UrlController::class, "createShortUrl"])->name("url.short");
Route::post('/verify-password/{code}', [UrlController::class, 'verifyPassword'])->name('verify.password');
