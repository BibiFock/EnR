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
        // if (App::runningUnitTests()) {
         // if ($request->isXmlHttpRequest()) {
            // return response()->json(
                // class_basename( $e ) . ' in ' .
                // basename( $e->getFile() ) . ' line ' . $e->getLine()
                // . ': ' . $e->getMessage(),
                // 500
            // );
            // return response()->json([
                    // 'message' => $e->getMessage(),
                    // 'file' => $e->getFile(),
                    // 'line' => $e->getLine(),
                    // // 'trace' => $e->getTrace()
                // ],
                // 500
            // );

        // }
        $request->format('json');
        return parent::render($request, $e);
    }
}
