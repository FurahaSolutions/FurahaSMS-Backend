<?php

namespace Okotieno\Procurement\Controllers;

use App\Http\Controllers\Controller;
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

}
