<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        IsUserAuth::class;
        IsAdmin::class;
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(function () {
            \App\Models\Url::where('user_id', null)
                ->where('is_active', false)
                ->where('expires_at', '<', now()->subDays(30))
                ->delete();
        })->daily();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
