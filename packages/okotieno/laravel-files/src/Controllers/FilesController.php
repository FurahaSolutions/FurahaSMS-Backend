<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 12/12/2019
 * Time: 11:28 AM
 */

namespace Okotieno\Files\Controllers;


use App\Http\Controllers\Controller;
use App\Models\FileDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @param User $user
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request, User $user)
  {
    $response = [];
    return response()->json($response);
  }


  /**
   * Store a newly created resource in storage.
   * @param Request $request
   * @param User $user
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request, User $user)
  {

//        return response()->json([
//            'saves' => true,
//            'message' => 'Successfully allocated units to the student',
//            'data' => []
//        ]);
  }

  /**
   * Display the specified resource.
   *
   * @param $fileDocument
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($fileDocumentId)
  {
    $fileDocument = FileDocument::find($fileDocumentId);
    if ($fileDocument != null) {
      return Storage::download($fileDocument->file_path);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function update()
  {

  }

  /**
   * Remove the specified resource from storage.
   *
   * @return void
   * @throws \Exception
   */
  public function destroy()
  {

  }
}

