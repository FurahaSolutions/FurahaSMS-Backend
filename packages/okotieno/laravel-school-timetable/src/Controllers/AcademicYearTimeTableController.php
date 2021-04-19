<?php

namespace Okotieno\TimeTable\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\TimeTable\Models\TimeTable;
use Okotieno\TimeTable\Models\TimeTableTimingTemplate;
use Okotieno\TimeTable\Requests\DeleteAcademicYearTimeTableRequest;
use Okotieno\TimeTable\Requests\StoreAcademicYearTimeTableRequest;
use Okotieno\TimeTable\Requests\UpdateAcademicYearTimeTableRequest;

class AcademicYearTimeTableController extends Controller
{
  public function index(AcademicYear $academicYear)
  {

    return response()->json($academicYear->timeTables);
  }

  public function show(AcademicYear $academicYear, TimeTable $timeTable)
  {
    $timeTable['timing'] = TimeTableTimingTemplate::find($timeTable->time_table_timing_id);
    return response()->json($timeTable);
  }

  public function store(AcademicYear $academicYear, StoreAcademicYearTimeTableRequest $request)
  {
    $timetable = $academicYear->timeTables()->create([
      'description' => $request['description'],
      'time_table_timing_template_id' => $request['timing']
    ]);
    return response()->json([
      'saved' => true,
      'message' => 'TimeTable Created Successfully',
      'data' => $timetable
    ])->setStatusCode(201);
  }

  public function update(AcademicYear $academicYear, UpdateAcademicYearTimeTableRequest $request, TimeTable $timeTable)
  {
    // TODO should update timetable
    return response()->json([
      'saved' => true,
      'message' => 'TimeTable Created Successfully',
      'data' => $timeTable
    ]);
  }

  public function destroy(AcademicYear $academicYear, DeleteAcademicYearTimeTableRequest $request, TimeTable $timeTable)
  {
    $timeTable->delete();
    return response()->json([
      'saved' => true,
      'message' => 'TimeTable Deleted Successfully'
    ]);
  }
}
