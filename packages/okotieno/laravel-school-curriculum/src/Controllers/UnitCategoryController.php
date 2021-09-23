<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\UnitCategory;
use Okotieno\SchoolCurriculum\Requests\CreateUnitCategoryRequest;
use Okotieno\SchoolCurriculum\Requests\DeleteUnitCategoryRequest;
use Okotieno\SchoolCurriculum\Requests\UpdateUnitCategoryRequest;

class UnitCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request)
  {
    if ($request->boolean('only_active')) {
      return response()->json(UnitCategory::active()->get());
    }

    return response()->json(UnitCategory::all());
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param CreateUnitCategoryRequest $request
   * @return JsonResponse
   */
  public function store(CreateUnitCategoryRequest $request): JsonResponse
  {
    $unitCategory = UnitCategory::createCategory($request);
    return response()->json([
      'saved' => true,
      'message' => 'Successfully created unit category',
      'data' => $unitCategory
    ])->setStatusCode(201);
  }

  /**
   * Display the specified resource.
   *
   * @param UnitCategory $unitCategory
   * @param Request $request
   * @return JsonResponse
   */
  public function show(UnitCategory $unitCategory, Request $request)
  {
    if ($request->boolean('include_units' )) {
      $unitCategory->units;
    }
    return response()->json(
      $unitCategory
    );
  }


  /**
   * Update the specified resource in storage.
   *
   * @param UpdateUnitCategoryRequest $request
   * @param UnitCategory $unitCategory
   * @return JsonResponse
   */
  public function update(UpdateUnitCategoryRequest $request, UnitCategory $unitCategory)
  {
    $unitCategory->name = $request->name;
    $unitCategory->active = $request->active;
    $unitCategory->save();
    return response()->json([
      'saved' => true,
      'message' => 'successfully deleted unit category',
      'data' => $unitCategory
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteUnitCategoryRequest $request
   * @param UnitCategory $unitCategory
   * @return JsonResponse
   * @throws \Exception
   */
  public function destroy(DeleteUnitCategoryRequest $request, UnitCategory $unitCategory)
  {
    $unitCategory->delete();
    return response()->json([
      'saved' => true,
      'message' => 'successfully deleted unit category'
    ]);
  }
}
