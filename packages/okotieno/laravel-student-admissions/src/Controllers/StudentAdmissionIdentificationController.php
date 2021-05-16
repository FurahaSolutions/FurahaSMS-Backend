<?php

namespace Okotieno\StudentAdmissions\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Okotieno\Students\Models\Student;
use Okotieno\StudentAdmissions\Requests\User\CreateStudentRequest;

class StudentAdmissionIdentificationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

  }


  /**
   * Store a newly created resource in storage.
   *
   * @param CreateStudentRequest $request
   * @return JsonResponse
   */
  public function store(CreateStudentRequest $request)
  {
    $user = User::createStudent($request);
    return response()->json([
      'saved' => true,
      'message' => 'Student Successfully Created',
      'data' => $user
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
    //
  }


  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param $student_id
   * @return JsonResponse
   */
  public function update(Request $request, $student_id)
  {
    $student = Student::where('id', $student_id)->first();
    $user = User::updateStudent($student, $request);
    return response()->json($user);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return Response
   */
  public function destroy($id)
  {
    //
  }
}
