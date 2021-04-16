<?php

namespace Okotieno\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Okotieno\Procurement\Models\ProcurementItemsCategory;
use Okotieno\Procurement\Models\ProcurementVendor;
use Okotieno\Procurement\Requests\ProcurementVendorCreateRequest;
use Okotieno\Procurement\Requests\ProcurementVendorDeleteRequest;
use Okotieno\Procurement\Requests\ProcurementVendorUpdateRequest;


class ProcurementVendorsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index()
  {
    return response()->json(ProcurementVendor::all());
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param ProcurementVendorCreateRequest $request
   * @return JsonResponse
   */
  public function store(ProcurementVendorCreateRequest $request): JsonResponse
  {
    $created_request = ProcurementVendor::create([
      'name' => $request->name,
      'description' => $request->description,
      'physical_address' => $request->physical_address,
    ]);
    if ($request->contactInfo) {
      foreach ($request->contactInfo['emails'] as $email) {
        $created_request->contacts()->create([
          'name' => $email['name'],
          'value' => $email['value'],
          'is_email' => true,
          'is_phone' => false,
        ]);
      }
      foreach ($request->contactInfo['phones'] as $phone) {
        $created_request->contacts()->create([
          'name' => $phone['name'],
          'value' => $phone['value'],
          'is_email' => false,
          'is_phone' => true,
        ]);
      }
    }
    foreach ($request->procurement_items_categories as $category) {
      $category = ProcurementItemsCategory::find($category);
      $created_request->delivers()->save($category);
    }

    return response()->json([
      'saved' => true,
      'message' => 'Procurement Vendor saved Successfully',
      'data' => [
        'id' => $created_request->id,
        'name' => $created_request->name,
        'description' => $created_request->description,
        'physical_address' => $created_request->physical_address,
        'contacts' => $created_request->contacts
      ]
    ])->setStatusCode(201);
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return Response
   */
  public function show($id)
  {
    $procurementRequest = ProcurementVendor::find($id);
    return $procurementRequest;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param ProcurementVendorUpdateRequest $request
   * @param ProcurementVendor $procurementVendor
   * @return JsonResponse
   */
  public function update(ProcurementVendorUpdateRequest $request, ProcurementVendor $procurementVendor)
  {
    $procurementVendor->update([
      'name' => $request->name,
      'description' => $request->description,
      'physical_address' => $request->physical_address,
    ]);

    // TODO Update contact details

    return response()->json([
      'saved' => true,
      'message' => 'Procurement Vendor saved Successfully',
      'data' => [
        'id' => $procurementVendor->id,
        'name' => $procurementVendor->name,
        'description' => $procurementVendor->description,
        'physical_address' => $procurementVendor->physical_address,
        'contacts' => $procurementVendor->contacts
      ]
    ])->setStatusCode(200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param ProcurementVendorDeleteRequest $request
   * @param $id
   * @return JsonResponse
   */
  public function destroy(ProcurementVendorDeleteRequest $request, $id)
  {
    ProcurementVendor::destroy($id);
    return response()->json([
      'saved' => true,
      'message' => 'Procurement Vendor deleted successfully'
    ]);
  }
}
