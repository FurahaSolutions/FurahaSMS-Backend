<?php


namespace Okotieno\SchoolAccounts\Database\Factories;


use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\SchoolAccounts\Models\TuitionFeeFinancialPlan;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\Semester;
use Okotieno\SchoolCurriculum\Models\UnitLevel;

class TuitionFeeFinancialPlanFactory extends \Illuminate\Database\Eloquent\Factories\Factory
{

    protected $model = TuitionFeeFinancialPlan::class;
    public function definition()
    {
        return [
          'amount' => $this->faker->numberBetween(5000, 100000),
          'class_level_id' => ClassLevel::factory()->create()->id,
          'unit_level_id' => UnitLevel::factory()->create()->id,
          'semester_id' => Semester::factory()->create()->id,
          'academic_year_id' => AcademicYear::factory()->create()->id
        ];
    }
}
