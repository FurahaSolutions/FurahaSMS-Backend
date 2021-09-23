<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Requests\CreateClassLevelUnitLevelRequest;


class ClassLevelUnitLevelsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request)
  {
    $classLevels = ClassLevel::all();
    foreach ($classLevels as $key => $classLevel) {
      $classLevels[$key]->taughtUnits;
    }
    return response()->json($classLevels);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param CreateClassLevelUnitLevelRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(CreateClassLevelUnitLevelRequest $request)
  {
    ClassLevel::saveUnitAllocations($request->get('allocations'));
    return response()->json([
      'saved' => true,
      'message' => 'Successfully saved Allocations'
    ]);
  }

}
