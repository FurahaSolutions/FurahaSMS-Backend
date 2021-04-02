<?php

namespace Okotieno\AcademicYear\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\AcademicYear\Models\Holiday;
use Okotieno\AcademicYear\Requests\CreateHolidayRequest;
use Okotieno\AcademicYear\Requests\DeleteHolidayRequest;
use Okotieno\AcademicYear\Requests\UpdateHolidayRequest;

class HolidayController extends Controller
{
  public function store(CreateHolidayRequest $request)
  {
    return response()->json([
      'saved' => true,
      'message' => 'Holiday successfully created',
      'data' => Holiday::create($request->all())
    ])->setStatusCode(201);
  }

  public function update(UpdateHolidayRequest $request, Holiday $holiday)
  {
    $holiday->update($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Holiday successfully updated',
      'data' => $holiday
    ])->setStatusCode(200);
  }

  public function destroy(DeleteHolidayRequest $request, Holiday $holiday)
  {
    $holiday->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Holiday successfully deleted',
    ])->setStatusCode(200);
  }
}
