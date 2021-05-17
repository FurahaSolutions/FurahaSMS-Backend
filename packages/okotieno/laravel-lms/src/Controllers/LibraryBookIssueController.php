<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\BookIssue;
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
    $response = [];
    $issued_books = BookIssue::all();

    foreach ($issued_books as $issued_book) {
//            $issued_book->user;
//            $issued_book->libraryBookItem;
      $response[] = [
        "name" => $issued_book->user->name,
        "id" => $issued_book->id,
        "book_id" => $issued_book->libraryBookItem->libraryBook->id,
        "category" => $issued_book->libraryBookItem->libraryBook->libraryClass->name,
        "publisher" => $issued_book->libraryBookItem->libraryBook->publisher,
        "publication_date" => $issued_book->libraryBookItem->libraryBook->publication_date,
        "title" => $issued_book->libraryBookItem->libraryBook->title,
        "ISBN" => $issued_book->libraryBookItem->libraryBook->ISBN,
        "ref" => $issued_book->libraryBookItem->ref,
        "borrowed_date" => $issued_book->issue_date,
        "due_date" => $issued_book->due_date,
        "returned_date" => $issued_book->returned_date,
      ];
    }
    return response()->json($response);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param StoreLibraryBookIssueRequest $request
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
}
