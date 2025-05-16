<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\Handler as MiddlewareHandler;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        \App\Console\Commands\MakeServiceCommand::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
	->withMiddleware(new MiddlewareHandler())
    ->withExceptions(function (Exceptions $exceptions) {
 
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if (!$request->expectsJson()) {
                return redirect()->route('page.login'); 
            }
            
            return response()->json(['message' => 'Unauthenticated'], 401);
        });
    })->create();
