<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\BookIssue;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\LMS\Requests\LibraryBookIssueDeleteRequest;
use Okotieno\LMS\Requests\StoreLibraryBookIssueRequest;

class LibraryBookIssueController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(Request $request)
  {
    $limit = 30;
    if ($request->limit !== null) {
      $limit = $request->limit;
    }
    $response = [];
    $issued_books = BookIssue::paginate($limit);
    foreach ($issued_books->items() as $issued_book) {
      $response[] = [
        "user" => $issued_book->libraryUser->user->name,
        "id" => $issued_book->id,
        "book_id" => $issued_book->libraryBookItem->libraryBook->id,
        "category" => $issued_book->categories,
        "publisher" => $issued_book->libraryBookItem->libraryBook->libraryBookPublishers,
        "publication_date" => $issued_book->libraryBookItem->libraryBook->publication_date,
        "title" => $issued_book->libraryBookItem->libraryBook->title,
        "ISBN" => $issued_book->libraryBookItem->libraryBook->ISBN,
        "ref" => $issued_book->libraryBookItem->ref,
        "borrowed_date" => $issued_book->issue_date,
        "due_date" => $issued_book->due_date,
        "returned_date" => $issued_book->returned_date,
      ];
    }
    return response()->json([
      'data' => $response,
      'total' => $issued_books->total()
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param StoreLibraryBookIssueRequest $request
   * @return JsonResponse
   */
  public function store(StoreLibraryBookIssueRequest $request)
  {
    $user = User::find($request->user_id);
    if ($user->library_suspended) {
      abort(403, 'User is currently suspended from using the library');
    }
    $book = LibraryBookItem::find($request->book_item_id);
    $user->libraryUser->hasBorrowedBook($book);

    return response()->json([
      'saved' => true,
      'message' => 'Book issued Successfully'
    ]);
  }

  public function destroy(LibraryBookIssueDeleteRequest $request, LibraryBookItem $issue)
  {
    $bookItem = $issue;
    $bookItem->markAsReturned();
    return response()->json([
      'saved' => true,
      'message' => 'Book Marked as Returned Successfully'
    ]);
  }
}
