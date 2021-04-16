<?php

namespace Okotieno\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\Procurement\Models\ProcurementBid;
use Okotieno\Procurement\Models\ProcurementTender;
use Okotieno\Procurement\Requests\ProcurementBidCreateRequest;

class ProcurementTenderBidsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param ProcurementTender $procurementTender
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(ProcurementTender $procurementTender)
  {
    return response()->json($procurementTender->procurementRequest->bids);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param ProcurementBidCreateRequest $request
   * @param ProcurementTender $procurementTender
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(ProcurementBidCreateRequest $request, ProcurementTender $procurementTender)
  {
    $bid = $procurementTender->procurementTenderBids()->create([
      'price_per_unit' => $request->price_per_unit,
      'description' => $request->description,
      'unit_description' => $request->unit_description,
      'vendor_id' => $request->vendor_id,
    ]);

    return response()->json([
      'saved' => true,
      'message' => 'Procurement Bid Successfully',
      'data' => $bid
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($id)
  {
//        $procurementRequest = ProcurementVendor::find($id);
//        return $procurementRequest;
  }


  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\jsonResponse
   */
  public function update(Request $request, $tenderId, $bidId)
  {
    // TODO-me add logic to amend bid

    if ($request->awarded !== null) {
      if (auth()->user()->can('award procurement tender')) {
        $bid = ProcurementBid::find($bidId);
        $bid->awarded = $request->awarded;
        $bid->save();
      } else {
        abort(403, 'You do not have permission to award procurement tender');
      }
    }

    return response()->json([
      'saved' => 'true',
      'message' => 'Bid Successfully awarded',
      'data' => $bid
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param $id
   * @return \Illuminate\Http\jsonResponse
   */
  public function destroy($id)
  {
    // ProcurementVendor::destroy($id);
    return response()->json([
      'saved' => true,
      'message' => 'Procurement Tender deleted successfully'
    ]);
  }
}
