<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $queryName = $request->name ? $request->name : '';
    $queryLimit = $request->limit ? $request->limit : 20;
    $users = User::where('first_name', 'like', '%' . $queryName . '%')
      ->orWhere('last_name', 'like', '%' . $queryName . '%')
      ->orWhere('middle_name', 'like', '%' . $queryName . '%')
      ->limit($queryLimit)
      ->get();

    if ($request->name) {
      return response()->json($users);
    }
  }

  public function update(UserProfileUpdateRequest $request, User $user): JsonResponse
  {
    $allRequestField = $request->all();
    foreach ($user->getFillable() as $field) {
      if (key_exists($field, $allRequestField)) {
        if($field === 'first_name' || $field === 'last_name'){
          $validation = [];
          $validation[$field] = 'required';
          $request->validate($validation);
        }
        $user[$field] =  $allRequestField[$field];
        $user->save();
      }
    }
    if ($request->profile_pic_id) {
      $user->saveProfilePic($request);
    }

    return response()->json([
      'saved' => true,
      'message' => 'User Info successfully saved'
    ]);

  }
}
