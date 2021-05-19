<?php

namespace Okotieno\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\Procurement\Models\ProcurementItemsCategory;
use Okotieno\Procurement\Models\ProcurementRequest;
use Okotieno\Procurement\Requests\ProcurementItemsCategoryCreateRequest;
use Okotieno\Procurement\Requests\ProcurementRequestCreateRequest;

class ProcurementItemsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(ProcurementItemsCategory::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ProcurementRequestCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProcurementItemsCategoryCreateRequest $request)
    {

        $created_request = ProcurementItemsCategory::create([
            'name' => $request->name,
        ]);
        return response()->json([
            'saved' => true,
            'message' => 'Book saved Successfully',
            'book' => [
                'id' => $created_request->id,
                'name' => $created_request->name
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProcurementRequest $procurementRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProcurementItemsCategory $procurementItemsCategory)
    {
        ProcurementRequest::destroy($procurementItemsCategory->id);
        return response()->json([
            'saved' => true,
            'message' => 'Procurement Item Category Successfully deletes'
        ]);
    }
}
