<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Requests\ClassLevelUnitLevelStoreRequest;
use Okotieno\SchoolCurriculum\Requests\CreateClassLevelRequest;


class ClassLevelUnitLevelsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
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
   * @param ClassLevelUnitLevelStoreRequest $request
   * @return JsonResponse
   */
  public function store(ClassLevelUnitLevelStoreRequest $request): JsonResponse
  {
    ClassLevel::saveUnitAllocations($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully saved Allocations'
    ])->setStatusCode(201);

  }

}
