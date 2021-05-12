<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Okotieno\LMS\Models\LibraryUser;
use Okotieno\LMS\Requests\LibraryUserStoreRequest;
use Okotieno\LMS\Requests\LibraryUserUpdateRequest;

class LibraryUserController extends Controller
{

  /**
   * Store a newly created resource in storage.
   *
   * @param LibraryUserStoreRequest $request
   * @return JsonResponse
   */
  public function store(LibraryUserStoreRequest $request)
  {
    $user = LibraryUser::create(['user_id' => $request->user_id]);
    return response()->json([
      'saved' => true,
      'message' => 'Successfully Created Library user',
      'data' => $user
    ])->setStatusCode(201);
  }

  public function show(User $user)
  {
    return response()->json([
      'id' => $user->id,
      'firstName' => $user->first_name,
      'lastName' => $user->last_name,
      'libraryUserId' => $user->LibraryUserId,
      'canBorrowBook' => $user->canBorrowBook,
      'libraryBlocked' => $user->libraryBlocked,
    ]);
  }

  public function update(LibraryUserUpdateRequest $request, User $user)
  {
    $user->libraryUser->blocked = $request->blocked;
    $user->libraryUser->save();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully updated library user status',
      'data' => $user
    ]);
  }
}
