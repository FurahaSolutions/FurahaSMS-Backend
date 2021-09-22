<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryBook;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\LMS\Requests\StoreLibraryBookItemRequest;
use Okotieno\LMS\Requests\UpdateLibraryBookItemRequest;
use Okotieno\LMS\Requests\DeleteLibraryBookItemRequest;

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
   * @return JsonResponse
   */
  public function store(StoreLibraryBookItemRequest $request)
  {
    $libraryBook = LibraryBook::find($request->get('library_book_id'));

    $procurement_date = Carbon::now();
    if ($request->procurement_date != null && $request->procurement_date != "") {
      $procurement_date = Carbon::create($request->procurement_date);
    }

    $libraryBookItem = $libraryBook->libraryBookItems()->create([
      'ref' => $request->ref,
      'procurement_date' => $procurement_date,
      'reserved' => $request->reserved
    ]);

    return response()->json([
      'saved' => true,
      'message' => 'Book saved Successfully',
      'data' => $libraryBookItem
    ])->setStatusCode(201);
  }



  /**
   * Update the specified resource in storage.
   *
   * @param UpdateLibraryBookItemRequest $request
   * @param LibraryBook $libraryBook
   * @param LibraryBookItem $libraryBookItem
   * @return JsonResponse
   */
  public function update(UpdateLibraryBookItemRequest $request, LibraryBookItem $libraryBookItem): JsonResponse
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
   * @return JsonResponse
   */
  public function destroy(DeleteLibraryBookItemRequest $request, LibraryBookItem $libraryBookItem)
  {
    $libraryBookItem->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Book Item Deleted Successfully',
    ]);
  }
}
