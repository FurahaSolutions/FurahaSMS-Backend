<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Okotieno\LMS\Models\LibraryUser;
use Okotieno\LMS\Requests\LibraryUserStoreRequest;

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

}
