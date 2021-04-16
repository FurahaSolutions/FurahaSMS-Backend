<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Requests\CreateClassLevelCategoryRequest;
use Okotieno\SchoolCurriculum\Models\ClassLevelCategory;

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
        return response()->json(ClassLevelCategory::createClassLevelCategory($request));
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
    public function update(Request $request, ClassLevelCategory $classLevelCategory)
    {
        return response()->json(ClassLevelCategory::updateClassLevelCategory($classLevelCategory, $request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ClassLevelCategory $classLevelCategory
     * @return void
     * @throws \Exception
     */
    public function destroy(ClassLevelCategory $classLevelCategory)
    {
        $classLevelCategory->delete();
    }
}
