<?php

namespace Okotieno\SchoolCurriculum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Database\Factories\ClassLevelCategoryFactory;
use Okotieno\SchoolCurriculum\Requests\CreateClassLevelCategoryRequest;

class ClassLevelCategory extends Model
{
  use softDeletes, HasFactory;

  protected $fillable = ['name', 'abbr'];
  public $timestamps = false;
  protected $hidden = ['deleted_at'];

  protected static function newFactory()
  {
      return ClassLevelCategoryFactory::new();
  }

  public function classLevels()
  {
    return $this->hasMany(ClassLevel::class);
  }

  public static function createClassLevelCategory(CreateClassLevelCategoryRequest $request)
  {
    $classLevelCategory = self::create($request->all());
    return $classLevelCategory;
  }

  public static function updateClassLevelCategory(ClassLevelCategory $classLevelCategory, Request $request)
  {
    $classLevelCategory->name = $request->name;
    $classLevelCategory->active = $request->active;
    $classLevelCategory->description = $request->description;
    $classLevelCategory->save();

    return $classLevelCategory;
  }

}
