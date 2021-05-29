<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryBook;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\LMS\Requests\StoreLibraryBookItemRequest;
use Okotieno\LMS\Requests\UpdateLibraryBookItemRequest;

class LibraryBookItemController extends Controller
{
  public function index(Request $request) {
    $response = [];
    $bookItems = LibraryBookItem::where('ref', 'LIKE', '%'.$request->book_ref.'%');
    if($request->boolean('borrowed_only')) {
      $bookItems = $bookItems->borrowed();
    }
    foreach ($bookItems->limit(20)->get() as $libraryBookItem) {
      $response[] = [
        'id' => $libraryBookItem->id,
        'ref' => $libraryBookItem->ref,
        'title' => $libraryBookItem->libraryBook->title
      ];
    }
    return response()->json($response);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param LibraryBook $libraryBook
   * @param StoreLibraryBookItemRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(LibraryBook $libraryBook, StoreLibraryBookItemRequest $request)
  {
    $procument_date = Carbon::now();
    if ($request->procurement_date != null && $request->procurement_date != "") {
      $procument_date = Carbon::createFromTimeString($request->procurement_date);
    }
    $libraryBook->libraryBookItems()->create([
      'ref' => $request->ref,
      'procurement_date' => $procument_date,
      'reserved' => $request->reserved
    ]);

    return response()->json([
      'saved' => true,
      'message' => 'Book saved Successfully',
    ]);
  }



  /**
   * Update the specified resource in storage.
   *
   * @param UpdateLibraryBookItemRequest $request
   * @param LibraryBook $libraryBook
   * @param LibraryBookItem $libraryBookItem
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(UpdateLibraryBookItemRequest $request, LibraryBook $libraryBook, LibraryBookItem $libraryBookItem)
  {
    $libraryBookItem->update([
      'ref' => $request->ref,
      'reserved' => $request->reserved,
      'procurement_date' => $request->procurement_date
    ]);
    return response()->json([
      'saved' => true,
      'message' => 'Book saved Successfully',
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param LibraryBook $libraryBook
   * @param LibraryBookItem $libraryBookItem
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(LibraryBook $libraryBook, LibraryBookItem $libraryBookItem)
  {
    LibraryBookItem::destroy($libraryBookItem->id);
    return response()->json([
      'saved' => true,
      'message' => 'Book Item Deleted Successfully',
    ]);
  }
}
