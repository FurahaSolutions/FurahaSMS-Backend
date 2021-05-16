<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 9/13/2019
 * Time: 10:15 PM
 */

namespace Okotieno\Students\Models;


use App\Traits\AppUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\GuardianAdmissions\Traits\hasGuardians;
use Okotieno\SchoolAccounts\Traits\paysFees;
use Okotieno\SchoolCurriculum\Traits\TakesCourses;
use Okotieno\SchoolStreams\Traits\BelongsToStream;
use Okotieno\Students\Database\Factories\StudentFactory;
use Okotieno\Students\Traits\unitAllocated;

class Student extends Model
{
  use AppUser, hasGuardians, TakesCourses, unitAllocated, paysFees, BelongsToStream, HasFactory;
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
