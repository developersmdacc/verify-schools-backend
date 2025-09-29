<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;

use PDOException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Custom DB exception handler
     */
    public function render($request, Throwable $e)
    {
        // Handle DB connection lost
        if ($e instanceof PDOException) {
            return response()->json([
                'error' => 'Database connection lost',
                'message' => 'The API could not connect to the database. Please try again later.',
            ], 500);
        }

        // Handle DB query failures (like syntax errors, missing table, etc.)
        if ($e instanceof QueryException) {
            return response()->json([
                'error' => 'Database query error',
                'message' => 'A database error occurred. Please try again later.',
            ], 500);
        }

        return parent::render($request, $e);
    }

    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

}
