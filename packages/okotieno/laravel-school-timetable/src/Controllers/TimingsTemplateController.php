<?php

namespace Okotieno\TimeTable\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\TimeTable\Models\TimeTableTimingTemplate;
use Okotieno\TimeTable\Requests\DeleteTimeTableTimingTemplateRequest;
use Okotieno\TimeTable\Requests\StoreTimeTableTimingTemplateRequest;
use Okotieno\TimeTable\Requests\UpdateTimeTableTimingTemplateRequest;

class TimingsTemplateController extends Controller
{
  public function index()
  {
    $response = [];
    foreach (TimeTableTimingTemplate::all() as $item) {
      $item->timings;
      $response[] = $item;
    }
    return response()->json($response);
  }

  public function store(StoreTimeTableTimingTemplateRequest $request)
  {
    $template = TimeTableTimingTemplate::create([
      'description' => $request['description']
    ]);
    if ($request ->timings !== null) {
      foreach ($request->timings as $timing) {
        $template->timings()->create($timing);
      }
    }

    return response()->json([
      'saved' => true,
      'message' => 'Successfully created timings template',
      'data' => $template
    ])->setStatusCode(201);
  }

  public function update(UpdateTimeTableTimingTemplateRequest $request,TimeTableTimingTemplate $timeTableTimingTemplate) {
    $timeTableTimingTemplate->update($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully updated timings template',
      'data' => $timeTableTimingTemplate
    ]);
  }
  public function destroy(DeleteTimeTableTimingTemplateRequest $request,TimeTableTimingTemplate $timeTableTimingTemplate) {
    $timeTableTimingTemplate->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted timings template',

    ]);
  }
  public function show(TimeTableTimingTemplate $timeTableTimingTemplate) {
    return response()->json($timeTableTimingTemplate);
  }
}
