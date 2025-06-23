<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UrlVisitController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Rutas publicas
Route::post("/login", [AuthController::class, "login"])->name("user.login");
Route::post("/register", [UserController::class, "createUser"])->name("user.register");
Route::get("/url/{shortCode}/visits-per-month", [UrlVisitController::class, "visitsPerMonthByShortCode"]);
Route::get("/urls-by-rating", [UrlController::class, "getAllUrlByRating"])->name("url.by.rating");

//Rutas privada
Route::middleware([IsUserAuth::class])->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post("/logout", "logout");
        Route::get("/me", "getUser");
    });

    Route::get('/test-api', function () {
        return response()->json(['message' => 'API funcionando correctamente']);
    });
    Route::post("/short-url", [UrlController::class, "createShortUrl"])->name("url.short.api");
    Route::get("/urls", [UrlController::class, "getAllUrls"])->name("url.urls");
    Route::get("/urls-user", [UrlController::class, "getAllUrlsByUserId"])->name("url.urls.user");
    Route::patch("/reactivate-url/{code}", [UrlController::class, "reactivateUrlByICode"])->name("url.reactivate");
    Route::patch("/deactivate-url/{code}", [UrlController::class, "deactivateUrlByICode"])->name("url.deactivate");
    Route::patch("/change-state-url/{code}", [UrlController::class, "changeUrlState"])->name("url.state");
    Route::patch("/change-privacy-url/{code}", [UrlController::class, "changeUrlPrivacy"])->name("url.privacy");
    Route::get("/stats/{code}", [UrlController::class, "statsShortUrl"])->name("url.stats");
});
