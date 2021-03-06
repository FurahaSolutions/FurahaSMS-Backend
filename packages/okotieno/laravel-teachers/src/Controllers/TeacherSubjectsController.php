<?php


namespace Okotieno\Teachers\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Okotieno\Teachers\Requests\TeacherSubjectStoreRequest;

class TeacherSubjectsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @param User $user
   * @return JsonResponse
   */
  public function index(Request $request, User $user)
  {
    $response = [];
    foreach ($user->teacher->teaches as $teach) {
      $response[] = [
        'id' => $teach['id'],
        'name' => $teach['name'],
        'unit_id' => $teach['unit_id'],
        'level' => $teach['level']
      ];
    }
    return response()->json($response);
  }


  /**
   * Store a newly created resource in storage.
   * @param Request $request
   * @param User $user
   * @return JsonResponse
   */
  public function store(TeacherSubjectStoreRequest $request, User $user)
  {
    $unassignedUnits = array_diff($user->teacher->teaches->pluck('id')->toArray(), $request->units);
    $user->teacher->teaches()->detach($unassignedUnits);

    $newAssignedUnits = array_diff($request->units, $user->teacher->teaches->pluck('id')->toArray());
    foreach ($newAssignedUnits as $unit) {
      $user->teacher->teaches()->save(UnitLevel::find($unit));
    }
    return response()->json([
      'saved' => true,
      'message' => 'Successfully allocated units to the teacher',
      'data' => $request->all()
    ])->setStatusCode(201);
  }
}
