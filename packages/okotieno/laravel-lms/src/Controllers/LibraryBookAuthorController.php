<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryBookAuthor;
use Okotieno\LMS\Requests\DeleteLibraryBookAuthorRequest;
use Okotieno\LMS\Requests\StoreLibraryBookAuthorRequest;
use Okotieno\LMS\Requests\UpdateLibraryBookAuthorRequest;

class LibraryBookAuthorController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    if ($request->author_id != null) {
      return response()->json(LibraryBookAuthor::find($request->author_id));
    }
    if ($request->name != null) {
      return response()->json(LibraryBookAuthor::where('name', 'LIKE', '%' . $request->name . '%')->get());
    }
    return response()->json(LibraryBookAuthor::all());
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param StoreLibraryBookAuthorRequest $request
   * @return JsonResponse
   */
  public function store(StoreLibraryBookAuthorRequest $request): JsonResponse
  {
    $createdBook = LibraryBookAuthor::create([
      'name' => $request->name
    ]);
    return response()->json([
      'saved' => true,
      'message' => 'Author saved Successfully',
      'data' => $createdBook
    ])->setStatusCode(201);
  }

  /**
   * Display the specified resource.
   *
   * @param LibraryBookAuthor $author
   * @return JsonResponse
   */
  public function show(LibraryBookAuthor $author): JsonResponse
  {
    return response()->json($author);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param UpdateLibraryBookAuthorRequest $request
   * @param LibraryBookAuthor $author
   * @return JsonResponse
   */
  public function update(UpdateLibraryBookAuthorRequest $request, LibraryBookAuthor $author): JsonResponse
  {
    $author->update($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Author Updated Successfully',
      'data' => LibraryBookAuthor::find($author->id)
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteLibraryBookAuthorRequest $request
   * @param LibraryBookAuthor $author
   * @return JsonResponse
   */
  public function destroy(DeleteLibraryBookAuthorRequest $request, LibraryBookAuthor $author): JsonResponse
  {
    $author->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Author Updated Successfully',
    ]);
  }
}
