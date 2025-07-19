<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle all exceptions
        $exceptions->reportable(function (Exception $e) {
            Log::error('Unhandled exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });

        // Handle 404 errors
        $exceptions->renderable(function (Exception $e, Request $request) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                Log::warning('404 Not Found', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_id' => auth()->id(),
                    'ip' => $request->ip()
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Page not found.',
                        'message' => 'The requested resource could not be found.'
                    ], 404);
                }

                return response()->view('errors.404', [], 404);
            }
        });

        // Handle 500 errors
        $exceptions->renderable(function (Exception $e, Request $request) {
            if ($e instanceof \ErrorException || $e instanceof \ParseError) {
                Log::critical('500 Internal Server Error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_id' => auth()->id(),
                    'ip' => $request->ip()
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Internal server error.',
                        'message' => 'Something went wrong. Please try again later.'
                    ], 500);
                }

                return response()->view('errors.500', [], 500);
            }
        });

        // Handle database connection errors
        $exceptions->renderable(function (Exception $e, Request $request) {
            if ($e instanceof \Illuminate\Database\QueryException || 
                $e instanceof \PDOException) {
                Log::critical('Database connection error', [
                    'message' => $e->getMessage(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_id' => auth()->id(),
                    'ip' => $request->ip()
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Database error.',
                        'message' => 'Unable to connect to database. Please try again later.'
                    ], 503);
                }

                return response()->view('errors.503', [], 503);
            }
        });

        // Handle validation errors
        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            Log::info('Validation error', [
                'errors' => $e->errors(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Validation failed.',
                    'message' => 'Please check your input and try again.',
                    'errors' => $e->errors()
                ], 422);
            }
        });

        // Handle authentication errors
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            Log::warning('Authentication error', [
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated.',
                    'message' => 'Please login to access this resource.'
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Please login to access this page.');
        });

        // Handle authorization errors
        $exceptions->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, Request $request) {
            Log::warning('Authorization error', [
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized.',
                    'message' => 'You do not have permission to access this resource.'
                ], 403);
            }

            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        });
    })->create();
