<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\ServiceUnavailableException $e, \Illuminate\Http\Request $request) {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Service Unavailable'], 503);
        }
        return response()->view('errors.offline', [], 503);
    });

    $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, \Illuminate\Http\Request $request) {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        return response()->view('errors.404', ['event' => \App\Models\Event::active()->first(), 'exception' => $e], 404);
    });
})->create();
