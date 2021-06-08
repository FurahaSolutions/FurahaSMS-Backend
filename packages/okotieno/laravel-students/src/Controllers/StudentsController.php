<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 12/12/2019
 * Time: 11:28 AM
 */

namespace Okotieno\Students\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Okotieno\Students\Models\Student;
use Okotieno\Students\Requests\StudentStoreRequest;
use Okotieno\Students\Requests\StudentUpdateRequest;

class StudentsController extends Controller
{
  public function index(Request $request)
  {
    if($request->school_id) {
      $student = Student::where('student_school_id_number', $request->school_id)->get()->first();
      if ($student == null) {
        return response()->json()->setStatusCode(204);
      }
      return response()->json([
        'id' => $student->user->id,
        'first_name' => $student->first_name,
        'last_name' => $student->last_name,
        'middle_name' => $student->middle_name,
        'other_names' => $student->other_names,
        'date_of_birth' => $student->date_of_birth,
        'birth_cert_number' => $student->birth_cert_number,
        'name_prefix_id' => $student->name_prefix_id,
        'gender_id' => $student->gender_id,
        'religion_id' => $student->gender_id,
      ]);
    }
    if ($request->q) {
      return User::where(
        'first_name', 'like', '%' . $request->q . '%'
      )->orWhere(
        'last_name', 'like', '%' . $request->q . '%'
      )->orWhere(
        'middle_name', 'like', '%' . $request->q . '%'
      )->get();
    }

    $students = DB::table('students')
      ->leftJoin('users', function ($join) use ($request) {
        $join->on('students.user_id', '=', 'users.id');
      })
      ->leftJoin('genders', function ($join) use ($request) {
        $join->on('genders.id', '=', 'users.gender_id');
      })
      ->leftJoin('student_unit_allocations', function ($join) use ($request) {
        $join->on('student_unit_allocations.student_id', '=', 'students.id');
      })
      ->leftJoin('academic_year_unit_allocations', function ($join) use ($request) {
        $join->on('student_unit_allocations.unit_allocation_id', '=', 'academic_year_unit_allocations.id');
      })
      ->leftJoin('academic_years', function ($join) use ($request) {
        $join->on('academic_year_unit_allocations.academic_year_id', '=', 'academic_years.id');
      })
      ->leftJoin('class_levels', function ($join) use ($request) {
        $join->on('academic_year_unit_allocations.class_level_id', '=', 'class_levels.id');
      })
      ->leftJoin('stream_student', function ($join) use ($request) {
        $join->on('stream_student.student_id', '=', 'students.id');
        $join->on('stream_student.academic_year_id', '=', 'academic_years.id');
        $join->on('stream_student.class_level_id', '=', 'class_levels.id');
      })
      ->leftJoin('streams', function ($join) use ($request) {
        $join->on('stream_student.stream_id', '=', 'streams.id');
      })
      ->select([
        'users.id as id',
        'academic_years.name as academic_year_name',
        'class_levels.name as class_level_name',
        'streams.id as stream_id',
        'streams.name as stream_name',
        'first_name',
        'last_name',
        'middle_name',
        'academic_years.id as academic_year_id',
        'class_levels.id as class_level_id',
        'email',
        'other_names',
        'birth_cert_number',
        'gender_id',
        'religion_id',
        'phone',
        'student_school_id_number as student_id',
        'date_of_birth',
        'name_prefix_id',
        'genders.name as gender_name',
        'genders.abbreviation as gender_abbreviation',

      ])
      ->distinct();

    $possibleParams = [
      ['key' => 'academic_years.id', 'type' => 'where', 'value' => $request->academicYear],
      ['key' => 'class_levels.id', 'type' => 'whereIn', 'value' => $request->classLevels],
      ['key' => 'stream_id', 'type' => 'whereIn', 'value' => $request->streams]
    ];
    foreach ($possibleParams as $possibleParam) {
      if ($possibleParam['value'] && $possibleParam['type'] === 'where') {
        $students = $students->where($possibleParam['key'], $possibleParam['value']);
      }
      if ($possibleParam['value'] && $possibleParam['type'] === 'whereIn') {
        $students = $students->whereIn($possibleParam['key'], $possibleParam['value']);
      }
    }

    if ($request->last) {

      $students = $students->orderBy('id', 'desc')
        ->limit($request->last);
    }

    return response()->json($students->get());

  }

  public function show(User $user)
  {

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
      'religion_id' => $user->religion_id,
      'student_id' => $user->student->student_school_id_number,
      'religion_name' => $user->religion_name,
      'gender_name' => $user->gender_name
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param StudentStoreRequest $request
   * @return JsonResponse
   */
  public function store(StudentStoreRequest $request)
  {
    $user = User::createStudent($request);
    return response()->json([
      'saved' => true,
      'message' => 'Student Successfully Created',
      'data' => $user
    ])->setStatusCode(201);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param StudentUpdateRequest $request
   * @param User $user
   * @return JsonResponse
   */
  public function update(StudentUpdateRequest $request, User $user)
  {
    $user = User::updateStudent($user, $request);
    return response()->json([
      'saved' => true,
      'message' => 'Student Successfully updated',
      'data' => $user
    ]);
  }

}
