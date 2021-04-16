<?php

namespace Okotieno\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\Procurement\Models\ProcurementRequest;
use Okotieno\Procurement\Requests\ProcurementRequestApprovalCreateRequest;
use Okotieno\Procurement\Requests\ProcurementRequestCreateRequest;

class ProcurementRequestTenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(ProcurementRequest::pendingTendering());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProcurementRequestCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProcurementRequestApprovalCreateRequest $request)
    {
        ProcurementRequest::find($request->procurement_request_id)
            ->approved()
            ->create([
                'approved' => $request->approve,
                'approved_by' => auth()->id()
            ]);
        return response()->json([
            'saved' => true,
            'message' => 'Book saved Successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProcurementRequest $procurementRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

    }
}
