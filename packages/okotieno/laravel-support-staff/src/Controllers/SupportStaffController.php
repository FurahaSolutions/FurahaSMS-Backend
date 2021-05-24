<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 12/12/2019
 * Time: 11:28 AM
 */

namespace Okotieno\SupportStaff\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Okotieno\PermissionsAndRoles\Models\Role;
use Okotieno\SupportStaff\Requests\SupportStaffStoreRequest;

class SupportStaffController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return jsonResponse
   */
  public function index()
  {
    $response = [];
    return response()->json($response);
  }

  /**
   * Store a newly created resource in storage.
   * @param SupportStaffStoreRequest $request
   * @return JsonResponse
   *
   */
  public function store(SupportStaffStoreRequest $request)
  {

    $role = Role::find($request->staff_type);
    if ($role->is_staff == true) {
      $user = User::where('email', $request->email)->first();
      if ($user == null) {
        $user = User::create($request->all());
      }
      $user->assignRole($role->name);
      $user = User::find($user->id);
      return response()->json([
        'saved' => true,
        'message' => 'Successfully created ' . $role->name,
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
          'religion_id' => $user->religion_id
        ]
      ]);
    } else {
      abort(416, 'Invalid Staff Type');
    }
  }

  /**
   * Display th specified resource.
   *
   * @param User $support_staff
   * @return JsonResponse
   */
  public function show(User $support_staff)
  {
    $response = $support_staff->toArray();
    $response['gender_name'] = $support_staff->gender_name;
    $response['religion_name'] = $support_staff->religion_name;
    return response()->json($response);
  }

}
