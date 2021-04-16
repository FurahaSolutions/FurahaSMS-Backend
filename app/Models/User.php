<?php

namespace App\Models;

use App\Traits\canSaveFileDocument;
use App\Traits\HasPasswordToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Okotieno\Gender\Traits\hasGender;
use Okotieno\GuardianAdmissions\Traits\canBeAGuardian;
use Okotieno\NamePrefix\Traits\hasNamePrefix;
use Okotieno\Procurement\Traits\canProcure;
use Okotieno\Religion\Traits\hasReligion;
use Okotieno\SchoolExams\Traits\hasSchoolExams;
use Okotieno\StudentAdmissions\Traits\canBeAStudent;
use Okotieno\StudyMaterials\Traits\canUploadStudyMaterials;
use Okotieno\TeacherAdmissions\Traits\canBeATeacher;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static find($id)
 */
class User extends Authenticatable
{
  use HasApiTokens,
    Notifiable,
    HasRoles,
    canBeAStudent,
    canBeATeacher,
    canBeAGuardian,
    hasNamePrefix,
    hasGender,
    hasReligion,
    canProcure,
    hasSchoolExams,
    canUploadStudyMaterials,
    canSaveFileDocument,
    HasPasswordToken,
    HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'password',
    'middle_name',
    'other_names',
    'gender_id',
    'religion_id',
    'date_of_birth',
    'birth_cert_number',
    'phone'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function setPassword($password)
  {
    $this->password = bcrypt($password);
    $this->save();
    return $this;
  }
}
