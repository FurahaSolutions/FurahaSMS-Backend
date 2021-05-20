<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryClassification;

class LibraryClassificationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index()
  {
    return response()->json(LibraryClassification::all());
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request)
  {
    $input = $request->all();
    LibraryClassification::create($input);
    return response()->json(['saved' => true]);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return JsonResponse
   */
  public function update(Request $request, $id)
  {
    $input = $request->all();
    $task = LibraryClassification::findOrFail($id);
    $task->update($input);
    return response()->json(['saved' => true]);
  }

}
