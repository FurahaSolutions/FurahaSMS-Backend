<?php

namespace Okotieno\Guardians\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\Guardians\Requests\CreateGuardianRequest;
use Okotieno\Guardians\Requests\GuardianUpdateRequest;

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
    if ($request->boolean('with-students') == true) {
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
            'name_title_id' => $student->name_prefix_id,
            'name_title' => $student->name_prefix_prefix,
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
    $response['namePrefix'] = $student->name_prefix_prefix;
    return response()->json($user);
  }

  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @param User $studentUser
   * @return JsonResponse
   */
  public function index(Request $request, User $studentUser): JsonResponse
  {
    $response = [];
    foreach ($studentUser->student->guardians as $guardian) {
      $response[] = [
        'id' => $guardian->user->id,
        'first_name' => $guardian->first_name,
        'last_name' => $guardian->first_name,
        'middle_name' => $guardian->first_name,
        'other_names' => $guardian->first_name,
        'gender_name' => $guardian->gender_name,
        'gender_id' => $guardian->gender_id,
        'religion_id' => $guardian->religion_id,
        'religion_name' => $guardian->religion_name,
        'date_of_birth' => $guardian->date_of_birth,
        'email' => $guardian->email,
        'phone' => $guardian->phone,
      ];
    }
    return response()->json($response);
  }

  /**
   * Store a newly created resource in storage.
   * @param CreateGuardianRequest $request
   * @param User $studentUser
   * @return mixed | void
   */
  public function store(CreateGuardianRequest $request, User $studentUser)
  {
    if (($student = $studentUser->student) != null) {
      $user = $student->createGuardian($request);
      return response()->json([
        'saved' => true,
        'message' => 'Successfully saved guardian',
        'data' => $user
      ]);
    }
    abort(422, 'Trying to assign a guardian to a non student');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param GuardianUpdateRequest $request
   * @param User $user
   * @return JsonResponse
   */
  public function update(GuardianUpdateRequest $request, User $user)
  {

    $user = User::updateGuardian($user, $request);
    return response()->json([
      'saved' => true,
      'message' => 'Teacher Successfully updated',
      'data' => $user
    ]);
  }

}
