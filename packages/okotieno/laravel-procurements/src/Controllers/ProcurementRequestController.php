<?php

namespace Okotieno\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Okotieno\Procurement\Models\ProcurementRequest;
use Okotieno\Procurement\Requests\ProcurementRequestCreateRequest;
use Okotieno\Procurement\Requests\ProcurementRequestUpdateRequest;

class ProcurementRequestController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index()
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param ProcurementRequestCreateRequest $request
   * @return JsonResponse
   */
  public function store(ProcurementRequestCreateRequest $request)
  {

    $created_request = ProcurementRequest::create([
      'name' => $request->name,
      'procurement_items_category_id' => $request->procurement_items_category_id,
      'quantity_description' => $request->quantity_description,
      'description' => $request->description,
      'requested_by' => auth()->id()
    ]);
    return response()->json([
      'saved' => true,
      'message' => 'Procurement Requests created Successfully',
      'data' => $created_request
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param ProcurementRequest $procurement
   * @return JsonResponse
   */
  public function show(ProcurementRequest $procurement)
  {
    $procurement->user;
    return response()->json($procurement);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param ProcurementRequestUpdateRequest $request
   * @param ProcurementRequest $procurement
   * @return JsonResponse
   */
  public function update(ProcurementRequestUpdateRequest $request, ProcurementRequest $procurement): JsonResponse
  {
    $procurement->update($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Procurement Requests updated Successfully',
      'data' => $procurement
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param ProcurementRequest $procurementRequest
   * @return JsonResponse
   */
  public function destroy($id)
  {
    ProcurementRequest::destroy($id);
    return response()->json([
      'saved' => true,
      'message' => 'Procurement Requests deleted successfully'
    ]);
  }
}
