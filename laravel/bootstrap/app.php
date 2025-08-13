<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function(Request $request, Throwable $e) {
            if ($request->is("api/*")) {
                return true;
            }
        });


        $exceptions->renderable(function(ValidationException $e, Request $request){
            if ($request->is("api/*")) {
                return response()->json([
                    "message" => "the given data was invalid",
                    "errors" => $e->errors()
                ], Response::HTTP_BAD_REQUEST);
            }
        });

        $exceptions->renderable(function(Throwable $e, Request $request) {
            if ($request->is("api/*")) {
                return response()->json([
                    "message" => "an unexpected error occured"
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    })->create();
