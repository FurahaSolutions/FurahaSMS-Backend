<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryBook;
use Okotieno\LMS\Models\LibraryBookAuthor;
use Okotieno\LMS\Models\LibraryBookPublisher;
use Okotieno\LMS\Models\LibraryBookTag;
use Okotieno\LMS\Models\LibraryClass;
use Okotieno\LMS\Requests\DeleteLibraryBookRequest;
use Okotieno\LMS\Requests\StoreLibraryBookRequest;
use Okotieno\LMS\Requests\UpdateLibraryBookRequest;

class LibraryBookController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(Request $request)
  {
    $book = LibraryBook::find($request->book_id);
    if ($book != null) {
      return response()->json($book->details());
    }

    if($request->title != null || $request->author != null || $request->publisher != null || $request->tag != null){
      $books = LibraryBook::filter($request)
        ->get()
        ->pluck('id')
        ->unique();
      return response()->json(LibraryBook::collectionDetails(LibraryBook::find($books)));
    }

    $libraryBooks = LibraryBook::all();
    return response()->json(LibraryBook::collectionDetails($libraryBooks));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param StoreLibraryBookRequest $request
   * @return JsonResponse
   */
  public function store(StoreLibraryBookRequest $request): JsonResponse
  {
    $created_book = LibraryBook::create([
      'title' => $request->title,
      'ISBN' => $request->ISBN,
      'publication_date' => $request->publication_date,
    ]);
    $created_book->libraryClasses()->save(LibraryClass::find($request->category));
    foreach ($request->authors as $author) {
      $created_book->libraryBookAuthors()->save(LibraryBookAuthor::find($author));
    }
    foreach ($request->publishers as $author) {
      $created_book->libraryBookPublishers()->save(LibraryBookPublisher::find($author));
    }

    foreach ($request->tags as $tag) {
      $created_book->libraryBookTags()->save(LibraryBookTag::find($tag));
    }

    return response()->json([
      'saved' => true,
      'message' => 'Successfully created library book',
      'data' => [
        'id' => $created_book->id,
        'title' => $created_book->title,
        'ISBN' => $created_book->ISBN,
        'publisher' => $created_book->publisher,
        'publication_date' => $created_book->publication_date,
        'category' => $created_book->library_class_id,
        'tags' => $created_book->libraryBookTags,
        'publishers' => $created_book->libraryBookPublishers,
        'libraryClasses' => $created_book->libraryClasses
      ]
    ])->setStatusCode(201);
  }

  /**
   * Display the specified resource.
   *
   * @param LibraryBook $libraryBook
   * @return JsonResponse
   */
  public function show(LibraryBook $libraryBook): JsonResponse
  {
    $libraryBook->libraryBookAuthors;
    $libraryBook->libraryBookItems;
    $libraryBook->libraryBookPublishers;
    $libraryBook->libraryBookTags;
    $libraryBook->libraryClasses;
    return response()->json($libraryBook);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param UpdateLibraryBookRequest $request
   * @param LibraryBook $libraryBook
   * @return JsonResponse
   */
  public function update(UpdateLibraryBookRequest $request, LibraryBook $libraryBook): JsonResponse
  {
    $libraryBook->update($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully updated library book',
      'data' => $libraryBook
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteLibraryBookRequest $request
   * @param LibraryBook $libraryBook
   * @return JsonResponse
   */
  public function destroy(DeleteLibraryBookRequest $request, LibraryBook $libraryBook): JsonResponse
  {
    $libraryBook->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted library book'
    ]);
  }
}
