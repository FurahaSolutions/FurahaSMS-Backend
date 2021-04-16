<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Okotieno\SchoolCurriculum\Requests\CreateUnitCategoryRequest;
use Okotieno\SchoolCurriculum\Models\UnitCategory;

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
        if ($request->active == 1) {
            return response()->json(UnitCategory::active()->get());
        }
        if ($request->id) {
            $unitCategory = response()->json(UnitCategory::find($request->id));
            return $unitCategory;
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
        return response()->json(UnitCategory::createCategory($request));
    }

    /**
     * Display the specified resource.
     *
     * @param UnitCategory $unitCategory
     * @param Request $request
     * @return UnitCategory
     */
    public function show(UnitCategory $unitCategory, Request $request)
    {
        if ($request->units == 1){
            $unitCategory->units;
        }
        return $unitCategory;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $unitCategory = UnitCategory::find($id);
        $unitCategory->name = $request->name;
        $unitCategory->active = $request->active;
        $unitCategory->save();
        return $unitCategory;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $unitCategory = UnitCategory::find($id);
        $unitCategory->delete();
    }
}
