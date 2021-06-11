<?php

namespace Okotieno\Teachers\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\Students\Requests\StudentUpdateRequest;
use Okotieno\Teachers\Models\Teacher;
use Okotieno\Teachers\Requests\TeacherStoreRequest;
use Okotieno\Teachers\Requests\TeacherUpdateRequest;

class TeachersController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @param User $user
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request, User $user)
  {
    $teachers = Teacher::all();
    //return Teacher::all();
    $response = [];
    foreach ($teachers as $teacher) {
      $response[] = $teacher->user;
    }
    return response()->json($response);
  }

  /**
   * Store a newly created resource in storage.
   * @param TeacherStoreRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(TeacherStoreRequest $request)
  {
    $user = User::where('email', $request->email)->first();
    if ($user == null) {
      $user = User::create($request->all());
    }
    $user->makeTeacher();
    $user = User::find($user->id);
    return response()->json([
      'saved' => true,
      'message' => 'Successfully created teacher',
      'data' => [
        'id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'middle_name' => $user->middle_name,
        'other_names' => $user->other_names,
        'birth_cert_number' => $user->birth_cert_number,
        'date_of_birth' => $user->date_of_birth,
        'email' => $user->email,
        'phone' => $user->phone,
        'name_prefix_id' => $user->name_prefix_id,
        'gender_id' => $user->gender_id,
        'religion_id' => $user->religion_id,
        'teacher_id' => $user->teacher->teacher_school_id_number
      ]
    ])->setStatusCode(201);
  }

  /**
   * Display the specified resource.
   *
   * @param $userId
   * @return \Illuminate\Http\jsonResponse
   */
  public function show($userId)
  {
    $user = User::find($userId);
    return response()->json([
      'id' => $user->id,
      'first_name' => $user->first_name,
      'last_name' => $user->last_name,
      'middle_name' => $user->middle_name,
      'other_names' => $user->other_names,
      'birth_cert_number' => $user->birth_cert_number,
      'date_of_birth' => $user->date_of_birth,
      'email' => $user->email,
      'phone' => $user->phone,
      'name_prefix_id' => $user->name_prefix_id,
      'gender_id' => $user->gender_id,
      'gender_name' => $user->gender_name,
      'religion_id' => $user->religion_id,
      'religion_name' => $user->religion_name,
      // 'student_id' => $user->student->student_school_id_number
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param StudentUpdateRequest $request
   * @param User $user
   * @return JsonResponse
   */
  public function update(TeacherUpdateRequest $request, User $user)
  {
    $user = User::updateTeacher($user, $request);
    return response()->json([
      'saved' => true,
      'message' => 'Teacher Successfully updated',
      'data' => $user
    ]);
  }
}

