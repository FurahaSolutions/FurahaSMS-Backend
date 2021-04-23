<?php


namespace Okotieno\AcademicYear\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\AcademicYearUnitAllocation;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\UnitLevel;

class AcademicYearUnitAllocationFactory extends Factory
{
  protected $model = AcademicYearUnitAllocation::class;

  public function definition()
  {
    return [
      'academic_year_id' => AcademicYear::factory()->create()->id,
      'unit_level_id' => UnitLevel::factory()->create()->id,
      'class_level_id' => ClassLevel::factory()->create()->id
    ];
  }
}
