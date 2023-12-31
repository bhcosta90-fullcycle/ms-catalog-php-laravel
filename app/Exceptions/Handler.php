<?php

namespace App\Exceptions;

use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Domain\Exceptions\EntityValidationException;
use BRCas\CA\Domain\Exceptions\ValidationNotificationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

    public function render($request, Throwable $e)
    {
        if ($e instanceof EntityNotFoundException) {
            $response = $this->showError($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof EntityValidationException) {
            $response = $this->showError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($e instanceof ValidationNotificationException) {
            $response = $this->showError($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $response ?? parent::render($request, $e);
    }

    private function showError(string $message, int $statusCode)
    {
        return response()->json([
            'message' => $message,
        ], $statusCode);
    }
}
