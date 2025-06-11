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

Route::get("{code}", [UrlController::class, "redirectToOriginalUrl"])->name("url.redirect");
Route::post("/short-url", [UrlController::class, "createShortUrl"])->name("url.short");