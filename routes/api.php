<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
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


Route::middleware('auth:api')->group(function () {
  Route::post('users/{user}/password-reset', [ResetPasswordController::class, 'adminPasswordReset']);
  Route::post('password/reset', [ResetPasswordController::class, 'reset']);
  Route::get('users/auth', [AuthController::class, 'user']);
  Route::get('users/auth/logout', [AuthController::class, 'logout']);
  Route::get('users', [UserController::class, 'index']);
  Route::patch('users/{user}', [UserController::class, 'update']);
  Route::get('/email/verify/{id}/{hash}',[VerificationController::class, 'verify'])
    ->name('verification.verify');
});

Route::middleware('guest')->group(function() {
  Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
  Route::post('/password/token', [ResetPasswordController::class, 'tokenLogin']);
});

