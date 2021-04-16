<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryBookTag;
use Okotieno\LMS\Requests\StoreLibraryBookTagRequest;

class LibraryBookTagController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    //
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param StoreLibraryBookTagRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(StoreLibraryBookTagRequest $request)
  {

    $input = [
      'name' => $request->name,
    ];
    $createdTag = LibraryBookTag::create($input);
    return response()->json([
      'message' => 'Tag Created Successfully',
      'saved' => true,
      'data' => $createdTag
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function show(LibraryBookTag $libraryBookTag)
  {
    return response()->json($libraryBookTag);
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

    $input = [
      'name' => $request->name,
    ];
    if ($request->active != null) {
      $input['active'] = $request->active;
    }
    $task = LibraryBookTag::findOrFail($id);
    $task->update($input);
    return response()->json([
      'message' => 'Tag Updated Successfully',
      'saved' => true,
      'data' => LibraryBookTag::find($id)
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy($id)
  {
    LibraryBookTag::destroy($id);
    return response()->json([
      'message' => 'Tag Deleted Successfully',
      'saved' => true,
    ]);
  }
}
