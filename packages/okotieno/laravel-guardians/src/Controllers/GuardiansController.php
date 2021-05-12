<?php

namespace Okotieno\Guardians\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\GuardianAdmissions\Requests\User\CreateGuardianRequest;

class GuardiansController extends Controller
{
  /**
   * Display the specified resource.
   *
   * @param User $user
   * @param Request $request
   * @return JsonResponse
   */
  public function show(User $user, Request $request): JsonResponse
  {
    $students = [];
    if($request->boolean('withStudents') == true) {
      if ($user->guardian != null) {
        foreach ($user->guardian->students as $student) {
          $students[] = [
            'id' => $student->user->id,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'middle_name' => $student->middle_name,
            'other_names' => $student->other_names,
            'birth_cert_number' => $student->birth_cert_number,
            'date_of_birth' => $student->date_of_birth,
            'email' => $student->email,
            'phone' => $student->phone,
            'name_prefix_id' => $student->name_prefix_id,
            'gender_id' => $student->gender_id,
            'religion_id' => $student->religion_id,
            'student_id' => $student->student_school_id_number
          ];
        }
        $user['students'] = $students;
      }
    }
    $response = $user;
    $response['genderName'] = $user->gender_name;
    $response['religionName'] = $user->religion_name;
    $response['firstName'] = $user->first_name;
    $response['lastName'] = $user->last_name;
    $response['middleName'] = $user->middle_name;
    $response['otherNames'] = $user->other_names;
    $response['students'] = $students;
    return response()->json($user);
  }

}
