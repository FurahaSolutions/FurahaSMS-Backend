<?php

namespace Okotieno\LMS\Controllers\Api;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Okotieno\LMS\Models\LibraryClassification;

class LibraryBookController extends Controller
{

  public function getMyAccount()
  {
    return auth()->user()->allBorrowedBooks();
  }


  public function getClasses(Request $request)
  {
    $response = [];
    $classes = LibraryClassification::ofType($request->classification)
      ->libraryClasses
      ->where('library_class_id', null);
    foreach ($classes as $key => $item) {
      $response[] = [
        'id' => $item['id'],
        'class' => $item['class'],
        'name' => $item['name'],
        'classes' => $item['classes'],
      ];
    }

    return response()->json($response);
  }

  public function get(Request $request)
  {
    //return $request->all();
    return LibraryClassification::all();
  }
}
