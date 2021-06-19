<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AdminPasswordResetRequest;
use App\Http\Requests\User\PasswordChangeRequest;
use App\Http\Requests\User\TokenLoginRequest;
use App\Models\PasswordToken;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
  public function adminPasswordReset(AdminPasswordResetRequest $request, User $user)
  {
    $user->setPassword($request->reset_password);
    return response()->json([
      'saved' => true,
      'message' => 'User Password Successfully reset'
    ]);
  }

  public function tokenLogin(TokenLoginRequest $request)
  {
    $user = PasswordToken::getUserForToken($request->token);
    if ($user === null) {
      throw new AuthenticationException('Invalid token provided');
    }
    $token = PasswordToken::getUserForToken($request->token)
      ->createToken('PersonalAccessToken', ['*']);
    return [
      'token_type' => 'Bearer',
      'expires_in' => $token->token->expires_at
        ->diffInSeconds($token->token->created_at),
      'access_token' => $token->accessToken
    ];
  }

  public function reset(PasswordChangeRequest $request)
  {
    $message = 'Invalid Old Password';
    if ($request->token) {
      $message = 'Invalid Or Expired Token';
      $user = PasswordToken::getUserForToken($request->token);
      if ($user == null) {
        $userAuthenticated = false;
      } else {
        $userAuthenticated = $user->id == auth()->id();
      }
    } else {
      $userAuthenticated = Hash::check($request->old_password, auth()->user()->getAuthPassword());
    }
    if ($userAuthenticated) {
      $user = auth()->user();
      $user->password = bcrypt($request->new_password);
      $user->save();
      return response()->json([
        'saved' => true,
        'message' => 'Successfully Changed user password'
      ]);
    } else {
      abort('403', $message);
    }
  }
}
