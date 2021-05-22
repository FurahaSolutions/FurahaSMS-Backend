<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware(['cors', 'preflight'])->group(function () {
//  Route::options('{id}', function () {
//  });
//});

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware('auth:api')
  ->post('/users/{user}/password-reset', [ResetPasswordController::class, 'adminPasswordReset']);

Route::middleware('auth:api')->group(function () {
  Route::get('users/auth/logout', 'Auth\\AuthController@logout');
  Route::get('users', [UserController::class, 'index']);
  Route::patch('users/{user}', [UserController::class, 'update']);
});

Route::post('/password/email', 'User\\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/token', 'User\\ResetPasswordController@tokenLogin');

Route::middleware('auth:api')->group(function () {
  Route::post('/password/reset', 'User\\ResetPasswordController@reset');
});

