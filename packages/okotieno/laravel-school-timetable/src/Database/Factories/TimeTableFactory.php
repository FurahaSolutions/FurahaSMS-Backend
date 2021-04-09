<?php


namespace Okotieno\TimeTable\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\TimeTable\Models\TimeTable;
use Okotieno\TimeTable\Models\TimeTableTimingTemplate;

class TimeTableFactory extends Factory
{
  protected $model = TimeTable::class;

  public function definition()
  {
    return [
      'description' => $this->faker->sentence,
      'academic_year_id' => AcademicYear::factory()->create()->id,
      'time_table_timing_template_id' => TimeTableTimingTemplate::factory()->create()->id
    ];
  }
}
