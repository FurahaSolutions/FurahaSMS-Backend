<?php

namespace Okotieno\Students\Models;


use App\Traits\AppUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\Guardians\Traits\HasGuardians;
use Okotieno\SchoolAccounts\Traits\paysFees;
use Okotieno\SchoolCurriculum\Traits\TakesCourses;
use Okotieno\SchoolStreams\Traits\BelongsToStream;
use Okotieno\Students\Database\Factories\StudentFactory;
use Okotieno\Students\Traits\unitAllocated;

class Student extends Model
{
  use AppUser, HasGuardians, TakesCourses, unitAllocated, paysFees, BelongsToStream, HasFactory;
  protected static function newFactory()
  {
    return StudentFactory::new();
  }

  protected $fillable = ['student_school_id_number'];

  public static function generateIdNumber()
  {
    return self::count() + 1;
  }
}
