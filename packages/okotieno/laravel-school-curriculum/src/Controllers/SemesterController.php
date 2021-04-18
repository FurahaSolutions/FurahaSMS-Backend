<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\Semester;
use Okotieno\SchoolCurriculum\Requests\CreateSemesterRequest;
use Okotieno\SchoolCurriculum\Requests\DeleteSemesterRequest;
use Okotieno\SchoolCurriculum\Requests\UpdateSemesterRequest;

class SemesterController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request)
  {
    if ($request->active) {
      return Semester::where('active', true)->get();
    }
    return response()->json(Semester::all());
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param CreateSemesterRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(CreateSemesterRequest $request)
  {
    $semester = Semester::create($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully created semester',
      'data' => $semester
    ])->setStatusCode(201);
  }

  /**
   * Display the specified resource.
   *
   * @param Semester $semester
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function show(Semester $semester, Request $request)
  {
    return response()->json($semester);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param Semester $semester
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(UpdateSemesterRequest $request, Semester $semester)
  {
    $semester->update($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully updated semester',
      'data' => $semester
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteSemesterRequest $request
   * @param Semester $semester
   * @return \Illuminate\Http\JsonResponse
   * @throws \Exception
   */
  public function destroy(DeleteSemesterRequest $request, Semester $semester)
  {
    $semester->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted semester'
    ]);
  }
}
