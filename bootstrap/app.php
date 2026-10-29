<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->has('debug') || env('APP_DEBUG', false)) {
                return response(
                    "<h1>Debug Error</h1><p><strong>" . get_class($e) . ":</strong> " . htmlspecialchars($e->getMessage()) . "</p>" .
                    "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>" .
                    "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>",
                    500
                );
            }
        });
    })->create();
