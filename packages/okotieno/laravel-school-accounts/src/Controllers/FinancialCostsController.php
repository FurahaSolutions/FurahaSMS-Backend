<?php

namespace Okotieno\SchoolAccounts\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Okotieno\SchoolAccounts\Models\FinancialCost;
use Okotieno\SchoolAccounts\Requests\FinancialCostDeleteRequest;
use Okotieno\SchoolAccounts\Requests\FinancialCostStoreRequest;

class FinancialCostsController extends Controller
{
  public function index()
  {
    return FinancialCost::all();
  }

  public function store(FinancialCostStoreRequest $request)
  {
    FinancialCost::saveCosts($request);
    return response()->json([
      'saved' => true,
      'message' => 'Successfully saved cost items',
      'data' => FinancialCost::all()
    ])->setStatusCode(201);
  }

  /**
   * @param FinancialCostDeleteRequest $request
   * @param FinancialCost $financialCost
   * @return JsonResponse
   */
  public function destroy(FinancialCostDeleteRequest $request, FinancialCost $financialCost): JsonResponse
  {
    $name = $financialCost->name;
    $financialCost->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted Cost "' . $name . '"'
    ]);
  }
}
