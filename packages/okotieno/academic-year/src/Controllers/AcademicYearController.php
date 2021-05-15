<?php

namespace Okotieno\AcademicYear\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Requests\AcademicYearRestoreRequest;
use Okotieno\AcademicYear\Requests\CreateAcademicYearRequest;
use Okotieno\AcademicYear\Requests\DeleteAcademicYearRequest;
use Okotieno\AcademicYear\Requests\UpdateAcademicYearRequest;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\UnitLevel;

class AcademicYearController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return \Illuminate\Http\jsonResponse
   */
  public function index(Request $request): jsonResponse
  {
    if (($request->boolean('deleted'))) {
      if (!auth()->user()->can('view deleted academic year')) {
        abort(403, 'You are not authorised to view deleted academic year');
      }
      return response()->json(AcademicYear::onlyTrashed()->get());
    }

    if ($request->archived !== null && !$request->boolean('archived')) {
      return response()->json(AcademicYear::whereNull('archived_at')->get());
    }

    if ($request->boolean('archived')) {
      return response()->json(AcademicYear::whereNotNull('archived_at')->get());
    }

    if ($request->archived !== null && !$request->boolean('archived')) {
      return response()->json(AcademicYear::whereNotNull('archived_at')->get());
    }

    return response()->json(AcademicYear::all());
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */

  /**
   * Store a newly created resource in storage.
   *
   * @param CreateAcademicYearRequest $request
   * @return JsonResponse
   */
  public function store(CreateAcademicYearRequest $request): JsonResponse
  {
    return response()->json(AcademicYear::createAcademicYear($request))->setStatusCode(201);
  }


  /**
   * Display the specified resource.
   *
   * @param AcademicYear $academicYear
   * @param Request $request
   * @return jsonResponse
   */
  public function show(AcademicYear $academicYear, Request $request): jsonResponse
  {
    $returnAcademicYear = [
      'id' => $academicYear->id,
      'name' => $academicYear->name,
      'start_date' => $academicYear->start_date,
      'end_date' => $academicYear->end_date,
      'class_level_allocations' => []
    ];
    if ($request->semesters == 1) {
      $returnAcademicYear['semesters'] = $academicYear->semesters;
    }
    if ($request->class_levels == 1) {
      $classLevels = [];
      foreach ($academicYear->classAllocations->groupBy('class_level_id')->toArray() as $key => $classAllocation) {

        $classLevel = ClassLevel::find($key);
        $units = [];
        foreach ($classAllocation as $_classAllocation) {
          $unitLevel = UnitLevel::find($_classAllocation['unit_level_id']);
          $unit = [
            'id' => $_classAllocation['id'],
            'name' => $unitLevel->unit->name,
            'level' => $unitLevel->name
          ];
          $units[] = $unit;
        }
        $classLevels[] = [
          'id' => $classLevel->id,
          'name' => $classLevel->name,
          'abbreviation' => $classLevel->abbreviation,
          'units' => $units
        ];
      }
      $returnAcademicYear['class_level_allocations'] = $classLevels;
    }
    return response()->json($returnAcademicYear);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param AcademicYear $academicYear
   * @return jsonResponse
   */
  public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear): JsonResponse
  {
    $academicYear->update($request->toArray());
    return response()->json([
      'saved' => true,
      'message' => 'Academic year successfully updated',
      'data' => $academicYear
    ]);
    // return response()->json($academicYear->updateClassLevelCategory($academicYear, $request));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteAcademicYearRequest $request
   * @param AcademicYear $academicYear
   * @return JsonResponse
   */
  public function destroy(DeleteAcademicYearRequest $request, AcademicYear $academicYear)
  {
    $academicYear->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted Academic Year'
    ]);

  }

  public function restore(AcademicYearRestoreRequest $request, $id)
  {
    $academicYear = AcademicYear::onlyTrashed()->where('id', $id)->first();
    $academicYear->deleted_at = null;
    $academicYear->save();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted Academic Year'
    ]);

  }
}
