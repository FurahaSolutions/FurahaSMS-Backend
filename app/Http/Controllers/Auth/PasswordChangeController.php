<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordChangeRequest;

class PasswordChangeController extends Controller
{
  public function save(PasswordChangeRequest $request)
  {
    $user = Auth()->user();
    $user->password = bcrypt($request->password);
    $user->save();
    return response()->json([
      'saved' => true
    ]);
  }
}
