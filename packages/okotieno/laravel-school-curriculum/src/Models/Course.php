<?php

namespace Okotieno\SchoolCurriculum\Models;

use Illuminate\Database\Eloquent\Model;
use Okotieno\SchoolCurriculum\Traits\TakenByStudents;

class Course extends Model
{
  use TakenByStudents;

  public function units()
  {
    return $this->belongsToMany(Unit::class);
  }

}
