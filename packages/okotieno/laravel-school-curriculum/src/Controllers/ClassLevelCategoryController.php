<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\ClassLevelCategory;
use Okotieno\SchoolCurriculum\Requests\CreateClassLevelCategoryRequest;
use Okotieno\SchoolCurriculum\Requests\DeleteClassLevelCategoryRequest;
use Okotieno\SchoolCurriculum\Requests\UpdateClassLevelCategoryRequest;

class ClassLevelCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index()
  {
    return response()->json(ClassLevelCategory::all());
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param CreateClassLevelCategoryRequest $request
   * @return JsonResponse
   */
  public function store(CreateClassLevelCategoryRequest $request)
  {
    $classLevelCategory = ClassLevelCategory::createClassLevelCategory($request);
        return response()->json([
          'saved' => true,
          'message' => 'Successfully created class level category',
          'data' => $classLevelCategory
        ])->setStatusCode(201);
    }

  /**
   * Display the specified resource.
   *
   * @param ClassLevelCategory $classLevelCategory
   * @param Request $request
   * @return JsonResponse
   */
  public function show(ClassLevelCategory $classLevelCategory, Request $request)
  {
    if ($request->class_level == 1) {
      $classLevelCategory->classLevels;
    }
    return response()->json($classLevelCategory);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param ClassLevelCategory $classLevelCategory
   * @return JsonResponse
   */
  public function update(UpdateClassLevelCategoryRequest $request, ClassLevelCategory $classLevelCategory)
  {
    $classLevelCategory = ClassLevelCategory::updateClassLevelCategory($classLevelCategory, $request);
    return response()->json([
      'saved' => true,
      'message' => 'Successfully updated class level category',
      'data' => $classLevelCategory
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteClassLevelCategoryRequest $request
   * @param ClassLevelCategory $classLevelCategory
   * @return JsonResponse
   * @throws \Exception
   */
  public function destroy(DeleteClassLevelCategoryRequest $request, ClassLevelCategory $classLevelCategory)
  {
    $classLevelCategory->delete();
    return response()->json([
      'saved' => true,
      'message' => 'successfully deleted class level category'
    ]);
  }
}
