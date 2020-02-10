<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        //Handle Validation exceptions
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception,$request);
        }
        //Handle model exceptions
        if($exception instanceof ModelNotFoundException){
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No {$modelName} exist with this Identifier",404);
        }
        //Handle Authentication exception
        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request,$exception);
        }
        //Handle Authorization exception
        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(),403);
        }
        //Handle NotFoundHTTP exception
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('The specified URL cannot be found',404);
        }
        //Handle MethodnotAllowed exception
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('The specified method for this request is invalid',405);
        }
        //Handle General exception
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }
        //Handle Query exception
        if($exception instanceof QueryException){
            $errorCode = $exception->errorInfo[1];
            if($errorCode === 1451){
                return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource',
                    409);
            }
        }
        //Handle Unexpected exception
        if(config('app.debug')) {
            return parent::render($request, $exception);
        }
        return $this->errorResponse('Unexpected Exception. Try later',500);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('unauthenticated',401);
    }
    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
            return $this->errorResponse($errors,422);
        }
}
