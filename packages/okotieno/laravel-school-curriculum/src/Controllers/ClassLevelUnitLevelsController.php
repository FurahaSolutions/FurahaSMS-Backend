<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Requests\CreateClassLevelRequest;


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
   * @param CreateClassLevelRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request)
  {
    ClassLevel::saveUnitAllocations($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully saved Allocations'
    ]);

  }

  /**
   * Display the specified resource.
   *
   * @param ClassLevel $classLevel
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function show(ClassLevel $classLevel, Request $request)
  {
    return response()->json($classLevel);
  }

}
