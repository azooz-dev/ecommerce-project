<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Psr\Log\LogLevel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

use function App\Helpers\errorResponse;

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
        $this->renderable(function (Throwable $e, $request) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        switch (true) {
            case $exception instanceof ValidationException:
                $errors = $exception->errors();
                return errorResponse($errors, 422);

            case $exception instanceof ModelNotFoundException:
                $modelName = strtolower(class_basename($exception->getModel()));
                return errorResponse('Does not exists any ' . $modelName . ' with the specified identification', 404);

            case $exception instanceof AuthenticationException:
                return errorResponse('Unauthenticated', 401);

            case $exception instanceof AuthorizationException:
                return errorResponse($exception->getMessage(), 403);

            case $exception instanceof NotFoundHttpException:
                return errorResponse("The specified URL cannot be found.", 404);

            case $exception instanceof MethodNotAllowedHttpException:
                return errorResponse("The specified method for the request invalid.", 405);

            case $exception instanceof HttpException:
                return errorResponse($exception->getMessage(), $exception->getStatusCode());

            case $exception->getCode() === 23000 && str_contains($exception->getMessage(), 'Integrity constraint violation'):
                return errorResponse('Cannot delete or update a parent row: a foreign key constraint fails.', 409);

            default:
                return parent::render($request, $exception);
        }
    }
}
