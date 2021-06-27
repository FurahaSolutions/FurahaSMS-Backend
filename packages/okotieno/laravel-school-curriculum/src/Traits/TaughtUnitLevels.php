<?php


namespace Okotieno\SchoolCurriculum\Traits;


use Okotieno\SchoolCurriculum\Models\UnitLevel;

trait TaughtUnitLevels
{
  public function taughtUnits()
  {
    return $this->belongsToMany(UnitLevel::class);
  }

  public static function saveUnitAllocations(array $all)
  {
    foreach ($all as $classLevel) {
      $classLevelSaved = self::find($classLevel['id']);
      $classLevelSaved->taughtUnits()->detach();
      $classLevelSaved->taughtUnits()->attach($classLevel['unitLevels']);
    }
  }
  public function unitLevels() {
    return $this->belongsToMany(UnitLevel::class, 'academic_year_unit_allocations')
      ->withPivot('academic_year_id');
  }
}
