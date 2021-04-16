<?php

namespace Okotieno\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Okotieno\Procurement\Models\ProcurementRequest;
use Okotieno\Procurement\Models\ProcurementTender;
use Okotieno\Procurement\Requests\ProcurementTenderCreateRequest;

class ProcurementTenderController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(Request $request)
  {

    if ($request->awarded === "1") {
      $procurementTenders = ProcurementTender::awarded()->get();

      $response = [];

      foreach ($procurementTenders as $procurementTender) {
        $procurementRequest = $procurementTender->procurementRequest;
        $awardedBid = $procurementTender->bids->filter(function ($bid) {
          return $bid['awarded'] == true;
        })[0];
        $fulfilled = $procurementTender->fulfilled;
        if ($procurementRequest !== null) {
          $response[] = [
            'id' => $procurementTender['id'],
            'requested_item_name' => $procurementRequest['name'],
            'request_id' => $procurementRequest['id'],
            'vendor_name' => $awardedBid['vendor_name'],
            'vendor_id' => $awardedBid['vendor_id'],
            'quantity' => $awardedBid['vendor_id'],
            'fulfilled' => $fulfilled ? $fulfilled->fulfilled : null
          ];
        }
      }

      return response()->json($response);
    }

    return response()->json(ProcurementRequest::approvedForBidding());
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param ProcurementTenderCreateRequest $request
   * @return JsonResponse
   */
  public function store(ProcurementTenderCreateRequest $request): JsonResponse
  {
    $created_request = ProcurementTender::create([
      'procurement_request_id' => $request->procurement_request_id,
      'expiry_datetime' => Carbon::createFromDate($request->expiry_datetime),
      'description' => $request->description
    ]);
    return response()->json([
      'saved' => true,
      'message' => 'Tender created Successfully',
      'tender' => [
        'id' => $created_request->id
      ]
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return Response
   */
  public function show($id)
  {
//        $procurementRequest = ProcurementVendor::find($id);
//        return $procurementRequest;
  }


  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function update(Request $request, $id)
  {

    $procurementTender = ProcurementTender::find($id);
    if ($request->awarded_to !== null) {
      $procurementTender->awarded_to = $request->awarded_to;
    }
    $procurementTender->save();
    return response()->json([
      'saved' => true,
      'message' => 'Procurement Tender successfully updated'
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param $id
   * @return Response
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
