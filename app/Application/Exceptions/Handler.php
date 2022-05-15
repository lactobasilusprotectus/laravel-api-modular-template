<?php

namespace App\Application\Exceptions;

use App\Interfaces\Http\Controllers\ResponseTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use \Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

    public function render($request, Throwable $e): Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $exceptionInstance = get_class($e);

        switch ($exceptionInstance){
            case AuthenticationException::class:
                $status = Response::HTTP_UNAUTHORIZED;
                $message = $e->getMessage();
                break;
            case AuthorizationException::class:
                $status = Response::HTTP_FORBIDDEN;
                $message = !empty($e->getMessage()) ? $e->getMessage() : 'Forbidden';
                break;
            case MethodNotAllowedHttpException::class:
                $status = Response::HTTP_METHOD_NOT_ALLOWED;
                $message = 'Method not allowed';
                break;
            case NotFoundHttpException::class:
            case ModelNotFoundException::class:
                $status = Response::HTTP_NOT_FOUND;
                $message = 'The requested resource was not found';
                break;
            case MaintenanceModeException::class:
                $status = Response::HTTP_SERVICE_UNAVAILABLE;
                $message = 'The API is down for maintenance';
                break;
            case QueryException::class:
                $status = Response::HTTP_BAD_REQUEST;
                $message = 'Internal error';
                break;
            case ThrottleRequestsException::class:
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $message = 'Too many Requests';
                break;
            default:
                $status = $e->getCode();
                $message = $e->getMessage();
                break;
        }

        if (!empty($status) && !empty($message)){
            return $this->respondWithCustomData([
                'message' => $message,
                $status
            ]);
        }

        return parent::render($request, $e);
    }
}
