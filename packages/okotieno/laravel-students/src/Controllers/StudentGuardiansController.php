<?php


namespace Okotieno\Students\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Okotieno\GuardianAdmissions\Requests\User\CreateGuardianRequest;

class StudentGuardiansController extends Controller
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
    foreach ($user->student->guardians as $guardian) {
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
   * @param User $user
   * @return mixed
   */
  public function store(CreateGuardianRequest $request, User $user)
  {
    if (($student = $user->student) != null) {
      $user = $student->createGuardian($request);
      return $user;
    }
  }

}
