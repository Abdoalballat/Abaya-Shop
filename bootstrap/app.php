<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // حل مشكلة الـ HTTPS والـ Logout على Vercel
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckAdminRole::class,
        ]);
    })
   ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (\Throwable $e, $request) {
        if ($request->is('admin*')) {
            return response(
                get_class($e) . "\n" . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine(),
                500,
                ['Content-Type' => 'text/plain; charset=utf-8']
            );
        }
    });
})->create();