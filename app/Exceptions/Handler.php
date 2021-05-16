<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    'password',
    'password_confirmation',
  ];

  /**
   * Report or log an exception.
   *
   * @param Exception $exception
   * @return void
   */
  public function report(Throwable $exception)
  {
    parent::report($exception);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param Request $request
   * @param Throwable $exception
   * @return Response
   * @throws Throwable
   */
  public function render($request, Throwable $exception)
  {
    return parent::render($request, $exception);
  }

//  protected function unauthenticated($request, AuthenticationException $exception)
//  {
//    if($request->expectsJson()) {
//      return response()->json(['message' =>  $exception->getMessage()],422);
//    }
//    return response()->json(['message' => 'unathenticated'], 401);
//
//  }
}
