<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryBookTag;
use Okotieno\LMS\Requests\DeleteLibraryBookTagRequest;
use Okotieno\LMS\Requests\StoreLibraryBookTagRequest;
use Okotieno\LMS\Requests\UpdateLibraryBookTagRequest;

class LibraryBookTagController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    if ($request->tag_id != null) {
      return LibraryBookTag::find($request->tag_id);
    }
    if($request->name) {
      return response()->json(
        LibraryBookTag::where('name', 'LIKE', '%' . $request->name . '%')->get()
      );
    }
    return response()->json(LibraryBookTag::all());

  }


  /**
   * Store a newly created resource in storage.
   *
   * @param StoreLibraryBookTagRequest $request
   * @return JsonResponse
   */
  public function store(StoreLibraryBookTagRequest $request): JsonResponse
  {
    $createdTag = LibraryBookTag::create($request->all());
    return response()->json([
      'message' => 'Tag Created Successfully',
      'saved' => true,
      'data' => $createdTag
    ])->setStatusCode(201);
  }

  /**
   * Display the specified resource.
   *
   * @param LibraryBookTag $tag
   * @return JsonResponse
   */
  public function show(LibraryBookTag $tag): JsonResponse
  {
    return response()->json($tag);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param UpdateLibraryBookTagRequest $request
   * @param LibraryBookTag $tag
   * @return JsonResponse
   */
  public function update(UpdateLibraryBookTagRequest $request, LibraryBookTag $tag): JsonResponse
  {

    $tag->update($request->all());
    return response()->json([
      'message' => 'Tag Updated Successfully',
      'saved' => true,
      'data' => $tag
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteLibraryBookTagRequest $request
   * @param LibraryBookTag $tag
   * @return JsonResponse
   */
  public function destroy(DeleteLibraryBookTagRequest $request ,LibraryBookTag $tag): JsonResponse
  {
    $tag->delete();
    return response()->json([
      'message' => 'Tag Deleted Successfully',
      'saved' => true,
    ]);
  }
}
