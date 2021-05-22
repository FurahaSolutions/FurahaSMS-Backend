<?php

namespace Okotieno\Teachers\Traits;


use Carbon\Carbon;
use Okotieno\Teachers\Models\Teacher;

trait CanBeATeacher {
    public function makeTeacher() {
        $this->teacher()->create([]);
        $this->assignRole('teacher');
    }
    public function teacher() {
        return $this->hasOne(Teacher::class);
    }

  public static function updateTeacher($user, $request)
  {

    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->middle_name = $request->middle_name;
    $user->other_names = $request->other_names;
    $user->gender_id = $request->gender_id;
    $user->birth_cert_number = $request->birth_cert_number;
    $user->date_of_birth = new Carbon($request->date_of_birth);
    $user->save();
    return [
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
    ];

  }
}
