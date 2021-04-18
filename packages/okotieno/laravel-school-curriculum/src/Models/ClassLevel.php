<?php

namespace Okotieno\SchoolCurriculum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolCurriculum\Database\Factories\ClassLevelFactory;
use Okotieno\SchoolCurriculum\Requests\UpdateClassLevelRequest;
use Okotieno\SchoolCurriculum\Requests\CreateClassLevelRequest;
use Okotieno\SchoolCurriculum\Traits\TaughtUnitLevels;


class ClassLevel extends Model
{
    use softDeletes, TaughtUnitLevels, HasFactory;
    protected $fillable = ['name', 'abbreviation', 'active'];
    public $timestamps = false;
    protected $hidden = ['deleted_at'];

    protected static function newFactory()
    {
      return ClassLevelFactory::new();
    }

  public static function createClassLevel(CreateClassLevelRequest $request)
    {
        $classLevelCategory = ClassLevelCategory::find($request->class_level_category_id);
        $classLevel = $classLevelCategory->classLevels()->create([
            'abbreviation' => $request->abbreviation,
            'name' => $request->name,
            'active' => $request->active,

        ]);
        return $classLevel;
    }

    public static function updateClassLevel(ClassLevel $classLevel, UpdateClassLevelRequest $request)
    {
        $classLevel->update([
            'class_level_category_id' => $request-> class_level_category_id,
            'name' => $request->name,
            'active' => $request->active,
            'abbreviation' => $request->abbreviation
        ]);
        return $classLevel;
    }


  public function unitLevels() {
        return $this->belongsToMany(UnitLevel::class, 'academic_year_unit_allocations')
            ->withPivot('academic_year_id');
    }
}
