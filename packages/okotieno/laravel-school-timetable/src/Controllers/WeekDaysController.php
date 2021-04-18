<?php

namespace Okotieno\TimeTable\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\TimeTable\Models\WeekDay;
use Okotieno\TimeTable\Requests\DeleteWeekDayRequest;
use Okotieno\TimeTable\Requests\StoreWeekDayRequest;
use Okotieno\TimeTable\Requests\UpdateWeekDayRequest;

class WeekDaysController extends Controller
{
  public function index()
  {
    return response()->json(WeekDay::all());
  }

  public function show(WeekDay $weekDay)
  {
    return response()->json($weekDay);
  }

  public function store(StoreWeekDayRequest $request)
  {
    $weekDay = WeekDay::create($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully created week day',
      'data' => $weekDay
    ])->setStatusCode(201);
  }

  public function update(UpdateWeekDayRequest $request, WeekDay $weekDay)
  {
    $weekDay->update($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully updated week day',
      'data' => $weekDay
    ]);
  }

  public function destroy(DeleteWeekDayRequest $request, WeekDay $weekDay)
  {
    $weekDay->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted week day',
    ]);
  }
}
