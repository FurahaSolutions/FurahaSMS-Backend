<?php


namespace Okotieno\Guardians\Traits;


use Carbon\Carbon;
use Okotieno\Guardians\Models\Guardian;

trait CanBeAGuardian
{
  public static function updateGuardian($guardianUser, $request)
  {

    $guardianUser->first_name = $request->first_name;
    $guardianUser->last_name = $request->last_name;
    $guardianUser->middle_name = $request->middle_name;
    $guardianUser->other_names = $request->other_names;
    $guardianUser->gender_id = $request->gender_id;
    $guardianUser->birth_cert_number = $request->birth_cert_number;
    $guardianUser->date_of_birth = new Carbon($request->date_of_birth);
    $guardianUser->save();
    return [
      'id' => $guardianUser->id,
      'first_name' => $guardianUser->first_name,
      'last_name' => $guardianUser->last_name,
      'middle_name' => $guardianUser->middle_name,
      'other_names' => $guardianUser->other_names,
      'birth_cert_number' => $guardianUser->birth_cert_number,
      'date_of_birth' => $guardianUser->date_of_birth,
      'email' => $guardianUser->email,
      'phone' => $guardianUser->phone,
      'name_prefix_id' => $guardianUser->name_prefix_id,
      'gender_id' => $guardianUser->gender_id,
      'religion_id' => $guardianUser->religion_id
    ];

  }

  public function guardian()
  {
    return $this->hasOne(Guardian::class);
  }
}
