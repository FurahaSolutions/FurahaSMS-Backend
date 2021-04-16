<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\LMS\Requests\StoreLibraryBookReturnRequest;

class LibraryBookReturnController extends Controller
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
   * @param StoreLibraryBookReturnRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(StoreLibraryBookReturnRequest $request)
  {
    $book = LibraryBookItem::withRef($request->ref);

    $error_messages = [];
    if ($book == null) {
      $error_messages['ref'] = ['The Book Reference is Invalid'];
    }
    if (sizeof($error_messages) > 0) {
      $error = \Illuminate\Validation\ValidationException::withMessages($error_messages);
      throw $error;
    }

    try {
      $book->markAsReturned();
    } catch (\Exception $e) {
      $error = \Illuminate\Validation\ValidationException::withMessages(
        ['ref' => ['The book with ref ' . $request->ref . ' has not been issued']]
      );
      throw $error;
    }

//        return $book;

    return response()->json([
      'saved' => true,
      'message' => 'Book Marked as Returned Successfully'
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
