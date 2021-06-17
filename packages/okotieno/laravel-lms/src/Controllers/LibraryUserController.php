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
  public function index()
  {
    $response = [];
    $limit = 15;
    $users = User::whereHas('libraryUser', function ($q) {
      return $q->where('suspended', false);
    })->limit($limit)->get();
    foreach ($users as $user) {
      $response[] = [
        'id' => $user->id,
        'firstName' => $user->first_name,
        'lastName' => $user->last_name,
        'suspended' => $user->libraryUser->suspended
      ];
    }
    return response()->json(
      $response
    );
  }

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
      'librarySuspended' => $user->librarySuspended,
    ]);
  }

  public function update(LibraryUserUpdateRequest $request, User $user)
  {
    $user->libraryUser->suspended = $request->suspended;
    $user->libraryUser->save();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully updated library user status',
      'data' => $user
    ]);
  }
}
