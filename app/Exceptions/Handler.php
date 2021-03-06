<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        // if (app()->environment('testing')
            // || $request->ajax()
        // ) {
            $trace = array_map( function($row) {
                    if (empty($row['file'])) {
                        return $row['class'] . '->' . $row['function'];
                    }
                    return $row['file'] . ':' . $row['line'];
                },
                $e->getTrace()
            );

            if (method_exists($e, 'getStatusCode')) {
                $status = $e->getStatusCode();
            }

            return response()->json([
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => (!app()->environment('testing') ? $trace : true)
                ],
                (!empty($status) ? $status : 500)
            );

        // }
        // return parent::render($request, $e);
    }
}
