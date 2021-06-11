<?php


namespace Okotieno\Teachers\Models;

use Okotieno\Teachers\Database\Factories\TeacherFactory;
use App\Traits\AppUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\Teachers\Traits\TeachesUnit;

class Teacher extends Model
{
  use AppUser, TeachesUnit, HasFactory;
  protected static function newFactory()
  {
    return TeacherFactory::new();
  }
}
