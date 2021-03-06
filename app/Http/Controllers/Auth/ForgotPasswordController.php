<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ResetPasswordEmailRequest;
use App\Mail\PasswordResetMail;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
  public function sendResetLinkEmail(ResetPasswordEmailRequest $request)
  {

    $token = bcrypt(Str::random(8));
    $user = User::where('email', $request->email)->first();
    $user->passwordToken()->create(['token' => $token, 'expires_at' => now()->addDay()]);
    $message_body = [
      'reset_link' => $token,
      'school_name' => Setting::schoolName(),
      'name' => $user->first_name
    ];
    Mail::to($user)->send(new PasswordResetMail($message_body));

    return [
      'saved' => true,
      'message' => 'Successfully sent Password reset email'
    ];
  }

}
