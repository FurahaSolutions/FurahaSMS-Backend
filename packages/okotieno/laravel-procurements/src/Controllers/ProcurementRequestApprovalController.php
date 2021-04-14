<?php

namespace Okotieno\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Okotieno\Procurement\Models\ProcurementRequest;
use Okotieno\Procurement\Requests\ProcurementRequestApprovalCreateRequest;

class ProcurementRequestApprovalController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    return response()->json(ProcurementRequest::pendingApproval());
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param ProcurementRequestApprovalCreateRequest $request
   * @return JsonResponse
   */
  public function store(ProcurementRequestApprovalCreateRequest $request): JsonResponse
  {
    ProcurementRequest::find($request->procurement_request_id)
      ->approved()
      ->create([
        'approved' => $request->approve,
        'approved_by' => auth()->id()
      ]);
    return response()->json([
      'saved' => true,
      'message' => 'Procurement approval successful',
    ]);
  }
}
