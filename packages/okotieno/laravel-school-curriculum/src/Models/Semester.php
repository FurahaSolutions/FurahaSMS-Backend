<?php

namespace Okotieno\SchoolCurriculum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Database\Factories\SemesterFactory;

class Semester extends Model
{
  use softDeletes, HasFactory;

  protected $fillable = ['name', 'abbreviation', 'active', 'semester_type_id'];
  public $timestamps = false;
  protected $hidden = ['deleted_at'];

  protected static function newFactory()
  {
    return SemesterFactory::new();
  }

  public function semesterType()
  {
    return $this->belongsTo(SemesterType::class);
  }

  public static function updateClassLevelCategory(Semester $semester, Request $request)
  {
    $semester->name = $request->name;
    $semester->active = $request->active;
    $semester->abbreviation = $request->abbreviation;
    $semester->save();

    return $semester;
  }

}
