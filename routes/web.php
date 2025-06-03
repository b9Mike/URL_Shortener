<?php

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/urls", [UrlController::class, "getAllUrls"])->name("url.urls");
Route::patch("/reactivate-url/{code}", [UrlController::class, "reactivateUrlByICode"])->name("url.reactivate");
Route::patch("/deactivate-url/{code}", [UrlController::class, "deactivateUrlByICode"])->name("url.deactivate");
Route::post("/short-url", [UrlController::class, "createShortUrl"])->name("url.short");
Route::get("{code}", [UrlController::class, "redirectToOriginalUrl"])->name("url.redirect");
Route::get("/stats/{code}", [UrlController::class, "statsShortUrl"])->name("url.stats");
