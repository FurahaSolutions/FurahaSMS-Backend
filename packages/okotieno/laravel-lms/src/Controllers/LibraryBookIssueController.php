<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\LMS\Requests\StoreLibraryBookIssueRequest;

class LibraryBookIssueController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param StoreLibraryBookRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(StoreLibraryBookIssueRequest $request)
  {
    $user = User::withIdNumber($request->id_number);
    $book = LibraryBookItem::withRef($request->ref);

    $error_messages = [];
    if ($user == null) {
      $error_messages['id_number'] = ['The School Id Number is Invalid'];
    }
    if ($book == null) {
      $error_messages['ref'] = ['The Book Reference is Invalid'];
    }
    if (sizeof($error_messages) > 0) {
      $error = \Illuminate\Validation\ValidationException::withMessages($error_messages);
      throw $error;
    }

    $user->hasBorrowedBook($book);

//        return $book;

    return response()->json([
      'saved' => true,
      'message' => 'Book issued Successfully'
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($id)
  {
    //
  }



  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy($id)
  {
    //
  }
}
