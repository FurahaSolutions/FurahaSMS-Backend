<?php

namespace Okotieno\AcademicYear\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\AcademicYear\Database\Factories\AcademicYearUnitAllocationFactory;
use Okotieno\Students\Traits\HasUnitAllocation;

class AcademicYearUnitAllocation extends Model
{
  use HasUnitAllocation, HasFactory;

  protected $fillable = [
    'academic_year_id',
    'unit_level_id',
    'class_level_id'
  ];

  protected static function newFactory()
  {
    return AcademicYearUnitAllocationFactory::new();
  }

  public static function allocate($academicYearId, $classLevelId, $unitLevelId)
  {
    return self::create([
      'academic_year_id' => $academicYearId,
      'class_level_id' => $classLevelId,
      'unit_level_id' => $unitLevelId,
    ]);
  }
}
