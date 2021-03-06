<?php

namespace Okotieno\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Okotieno\Procurement\Models\ProcurementFulfill;
use Okotieno\Procurement\Models\ProcurementTender;
use Okotieno\Procurement\Requests\ProcurementTenderCreateRequest;

class ProcurementTenderFulfillmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {


        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param ProcurementTender $procurementTender
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, ProcurementTender $procurementTender)
    {
        if ($procurementTender->fulfilled !== null) {
            abort(409, 'Item fulfilment already set');
        }
        $created_request = $procurementTender->fulfilled()->create([
            'comment' => 'comment',
            'fulfilled' => $request->fulfilled,
            'entered_by' => auth()->id()
        ]);
        $message = 'Tender Marked as Fulfilled Successfully';
        if ($request->fulfilled == 0) {
            $message = 'Tender Marked as Not Fulfilled Successfully';
        }
        return response()->json([
            'saved' => true,
            'message' => $message,
            'fulfill' => $created_request
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

//        $procurementTender = ProcurementTender::find($id);
//        if ($request->awarded_to !== null) {
//            $procurementTender->awarded_to = $request->awarded_to;
//        }
//        $procurementTender->save();
//        return response()->json([
//            'saved' => true,
//            'message' => 'Procurement Tender successfully updated'
//        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // ProcurementVendor::destroy($id);
//        return response()->json([
//            'saved' => true,
//            'message' => 'Procurement Tender deleted successfully'
//        ]);
    }
}
