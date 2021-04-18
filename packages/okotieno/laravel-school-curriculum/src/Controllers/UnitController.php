<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\Unit;
use Okotieno\SchoolCurriculum\Requests\CreateUnitRequest;
use Okotieno\SchoolCurriculum\Requests\DeleteUnitRequest;
use Okotieno\SchoolCurriculum\Requests\UpdateUnitRequest;

class UnitController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request)
  {
    $units = Unit::all();
    if ($request->unit_levels) {
      foreach ($units as $index => $unit) {
        $units[$index]->unitLevels;
      }
    }
    return response()->json($units);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param CreateUnitRequest $request
   * @return JsonResponse
   */
  public function store(CreateUnitRequest $request)
  {
    $unit = Unit::createSubject($request);
    return response()->json([
      'saved' => true,
      'message' => 'successfully created unit',
      'data' => $unit
    ])->setStatusCode(201);;
  }

  /**
   * Display the specified resource.
   *
   * @param Unit $unit
   * @param Request $request
   * @return JsonResponse
   */
  public function show(Unit $unit, Request $request)
  {
    if ($request->include_unit_levels == 1) {
      $unit->unitLevels;
      foreach ($unit->unitLevels as $key => $unitLevel) {
        $unit->unitLevels[$key]->semesters;
      }

    };
    return response()->json($unit);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param UpdateUnitRequest $request
   * @param Unit $unit
   * @return JsonResponse
   */
  public function update(UpdateUnitRequest $request, Unit $unit)
  {
    $unit = Unit::updateSubject($unit, $request);
    return response()->json([
      'saved' => true,
      'message' => 'successfully created unit',
      'data' => $unit
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteUnitRequest $request
   * @param Unit $unit
   * @return JsonResponse
   * @throws \Exception
   */
  public function destroy(DeleteUnitRequest $request, Unit $unit)
  {
    $unit->delete();
    return response()->json([
      'saved' => true,
      'message' => 'successfully deleted unit',
      'data' => $unit
    ]);

  }
}
