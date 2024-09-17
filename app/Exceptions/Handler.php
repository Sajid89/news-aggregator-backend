<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Log;
use PDOException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        // Use default Laravel logging
        Log::error('Exception occurred: ' . $exception->getMessage(), [
            'exception' => $exception,
        ]);

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Handle ValidationException
        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $exception->validator->getMessageBag(),
            ], 422);
        }

        // Handle ModelNotFoundException
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Handle AuthenticationException
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        // Handle AuthorizationException
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Handle PDOException (for database-specific errors)
        if ($exception instanceof PDOException) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $exception->getMessage(),
            ], 500);
        }

        // Catch-all for other exceptions
        Log::error('Exception occurred: ' . $exception->getMessage(), [
            'url' => $request->url(),
            'exception' => $exception,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Internal Server Error',
        ], 500);
    }
}
