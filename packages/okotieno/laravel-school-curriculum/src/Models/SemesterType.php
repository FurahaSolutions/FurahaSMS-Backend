<?php

namespace Okotieno\SchoolCurriculum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Database\Factories\SemesterTypeFactory;

class SemesterType extends Model
{
  use softDeletes, HasFactory;

  protected $fillable = ['name', 'active', 'default'];
  public $timestamps = false;
  protected $hidden = ['deleted_at'];

  protected static function newFactory()
  {
    return SemesterTypeFactory::new();
  }

  public function semester()
  {
    return $this->hasMany(SemesterType::class);
  }

  public static function defaultType()
  {
    return self::where('default', true)->first();
  }

  public static function createSemesterType(CreateSemesterTypeRequest $request)
  {
    $semester = self::create($request->all());
    return $semester;
  }

  public static function updateClassLevelCategory(SemesterType $semesterType, Request $request)
  {
    $semesterType->name = $request->name;
    $semesterType->active = $request->active;
    $semesterType->abbreviation = $request->abbreviation;
    $semesterType->save();

    return $semesterType;
  }

}
