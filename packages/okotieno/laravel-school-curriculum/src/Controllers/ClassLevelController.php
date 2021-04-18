<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Requests\CreateClassLevelRequest;
use Okotieno\SchoolCurriculum\Requests\DeleteClassLevelRequest;
use Okotieno\SchoolCurriculum\Requests\UpdateClassLevelRequest;


class ClassLevelController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request)
  {
    $classLevels = ClassLevel::all();
    if ($request->units) {
      foreach ($classLevels as $classLevel) {
        $classLevel->units;
      }
    }
    if ($request->include_levels) {
      foreach ($classLevels as $key => $classLevel) {
        if ($request->academic_year_id) {
          $classLevels[$key]['unit_levels'] = $classLevel->unitLevels()
            ->wherePivot('academic_year_id', '=', $request->academic_year_id)->get();
          foreach ($classLevels[$key]['unit_levels'] as $key1 => $unitLevel) {
            $classLevels[$key]['unit_levels'][$key1]->semesters;
          }
        } else {

          foreach ($classLevel->unitLevels as $key1 => $unitLevel) {
            $classLevel->unitLevels['unit_levels'][$key1]->semesters;
          }
        }


      }
    }
    return response()->json($classLevels);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param CreateClassLevelRequest $request
   * @return JsonResponse
   */
  public function store(CreateClassLevelRequest $request)
  {
    $classLevel = ClassLevel::createClassLevel($request);
    return response()->json([
      'saved' => true,
      'message' => 'successfully created class level',
      'data' => $classLevel
    ])->setStatusCode(201);

  }

  /**
   * Display the specified resource.
   *
   * @param ClassLevel $classLevel
   * @param Request $request
   * @return JsonResponse
   */
  public function show(ClassLevel $classLevel, Request $request)
  {
    return response()->json($classLevel);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param ClassLevel $classLevel
   * @param \Illuminate\Http\Request $request
   * @return JsonResponse
   */
  public function update(ClassLevel $classLevel, UpdateClassLevelRequest $request)
  {
    $classLevel = ClassLevel::updateClassLevel($classLevel, $request);
    return response()->json([
      'saved' => true,
      'message' => 'successfully updated class level',
      'data' => $classLevel
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteClassLevelRequest $request
   * @param ClassLevel $classLevel
   * @return JsonResponse
   * @throws \Exception
   */
  public function destroy(DeleteClassLevelRequest $request, ClassLevel $classLevel)
  {
    $classLevel->delete();
    return response()->json([
      'saved' => true,
      'message' => 'successfully deleted class level'
    ]);
  }
}
