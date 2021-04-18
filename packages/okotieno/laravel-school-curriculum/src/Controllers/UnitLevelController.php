<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Okotieno\SchoolCurriculum\Models\Unit;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Okotieno\SchoolCurriculum\Requests\CreateUnitLevelRequest;
use Okotieno\SchoolCurriculum\Requests\DeleteUnitLevelRequest;
use Okotieno\SchoolCurriculum\Requests\UpdateUnitLevelRequest;

class UnitLevelController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request)
  {
    if ($request->academic_year_id != null || $request->class_level_id != null) {
      $unitLevels = DB::table('unit_levels')
//                ->where('users.id', $user->id)
        ->leftJoin('academic_year_unit_allocations', function ($join) {
          $join->on('academic_year_unit_allocations.unit_level_id', '=', 'unit_levels.id');
        });

      if ($request->academic_year_id != null) {
        $unitLevels = $unitLevels->where(
          'academic_year_unit_allocations.academic_year_id',
          $request->academic_year_id
        );
      }
      if ($request->class_level_id != null) {
        $unitLevels = $unitLevels->where(
          'academic_year_unit_allocations.class_level_id',
          $request->class_level_id
        );
      }

      return response()->json(
        $unitLevels
          ->select(['name as unit_level_name', 'academic_year_id', 'class_level_id', 'unit_level_id'])
          ->distinct()->get()
      );
    }

    $unitLevels = [];
    if ($request->unit) {
      return Unit::find($request->unit)->unitLevels->map(function ($itemInner) {
        return [
          'id' => $itemInner->id,
          'name' => $itemInner->name
        ];
      });
    }

    foreach (Unit::all() as $item) {
      // return $item;
      $items = $item->unitLevels->map(function ($itemInner) use ($item) {
        // return $itemInner;
        return [
          'id' => $itemInner->id,
          'name' => $itemInner->name,
          "level" => $itemInner->level,
          "unit_id" => $itemInner->unit_id
        ];
      });
      $unitLevels = array_merge($unitLevels, $items->toArray());
    }
    return response()->json($unitLevels);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(CreateUnitLevelRequest $request)
  {
    $unitLevel = UnitLevel::create($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully created unit level',
      'data' => $unitLevel
    ])->setStatusCode(201);
  }

  /**
   * Display the specified resource.
   *
   * @param UnitLevel $unitLevel
   * @return JsonResponse
   */
  public function show(UnitLevel $unitLevel)
  {
    return response()->json($unitLevel);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param UpdateUnitLevelRequest $request
   * @param UnitLevel $unitLevel
   * @return JsonResponse
   */
  public function update(UpdateUnitLevelRequest $request, UnitLevel $unitLevel)
  {
    $unitLevel->update($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Unit Level Successfully deleted',
      'data' => $unitLevel
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteUnitLevelRequest $request
   * @param UnitLevel $unitLevel
   * @return JsonResponse
   * @throws Exception
   */
  public function destroy(DeleteUnitLevelRequest $request, UnitLevel $unitLevel)
  {
    $unitLevel->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Unit Level Successfully deleted'
    ]);
  }
}
