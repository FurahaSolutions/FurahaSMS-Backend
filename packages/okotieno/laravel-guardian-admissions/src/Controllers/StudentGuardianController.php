<?php

namespace Okotieno\GuardianAdmissions\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Okotieno\GuardianAdmissions\Requests\User\CreateGuardianRequest;
use Okotieno\Students\Models\Student;

class StudentGuardianController extends Controller
{

  /**
   * Store a newly created resource in storage.
   *
   * @param CreateGuardianRequest $request
   * @return JsonResponse
   */
  public function store(CreateGuardianRequest $request)
  {

    $student = Student::where('student_school_id_number', $request->student_id)->first();
    $user = $student->createGuardian($request);
    return $user;
  }
}
