<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {   //add Accept: application/json in request
            return $this->handleApiException($request, $exception);
        } else {
            $retval = parent::render($request, $exception);
        }
    
        return $retval;
        // if($request->wantsJson()){
        //     if ($exception instanceof AuthenticationException) {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'Unauthenticated',
        //             'errors' => [
        //                 'Unauthenticated'
        //             ]
        //         ], 401);
        //     }
    
        //     if($exception instanceof AuthorizationException){
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'This action is unauthorized.',
        //             'errors' => [
        //                 'This action is unauthorized.'
        //             ]
        //         ], 403);
        //     }
    
        //     if ($exception instanceof ModelNotFoundException) {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'Entry for '.str_replace('App\\Model\\', '', $exception->getModel()).' not found',
        //             'errors' => [
        //                 'Entry for ' . str_replace('App\\Model\\', '', $exception->getModel()) . ' not found'
        //             ]
        //         ], 404);
        //     }
    
        //     if ($exception instanceof ValidationException) {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'The given data was invalid.',
        //             'errors' => collect($exception->errors())->flatten()
        //         ], 422);
        //     }
    
        // }
    
        // return parent::render($request, $exception);
    }
    private function handleApiException($request, Exception $exception)
{
    $exception = $this->prepareException($exception);

    if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
        $exception = $exception->getResponse();
    }

    if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
        $exception = $this->unauthenticated($request, $exception);
    }

    if ($exception instanceof \Illuminate\Validation\ValidationException) {
        $exception = $this->convertValidationExceptionToResponse($exception, $request);
    }

    return $this->customApiResponse($exception);
}
private function customApiResponse($exception)
{
    if (method_exists($exception, 'getStatusCode')) {
        $statusCode = $exception->getStatusCode();
    } else {
        $statusCode = 500;
    }

    $response = [];

    switch ($statusCode) {
        case 401:
            $response['message'] = 'Unauthorized';
            break;
        case 403:
            $response['message'] = 'Forbidden';
            break;
        case 404:
            $response['message'] = 'Not Found';
            break;
        case 405:
            $response['message'] = 'Method Not Allowed';
            break;
        case 422:
            $response['message'] = $exception->original['message'];
            $response['errors'] = $exception->original['errors'];
            break;
        default:
            $response['message'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();
            break;
    }

    if (config('app.debug')) {
        $response['trace'] = $exception->getTrace();
        $response['code'] = $exception->getCode();
    }

    $response['status'] = $statusCode;

    return response()->json($response, $statusCode);
}
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            return false;
        });
    }
}
