<?php


namespace Okotieno\SchoolCurriculum\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolCurriculum\Models\Semester;
use Okotieno\SchoolCurriculum\Models\SemesterType;
use Okotieno\SchoolCurriculum\Models\Unit;
use Okotieno\SchoolCurriculum\Models\UnitCategory;

class SemesterFactory extends Factory
{
  protected $model = Semester::class;

  public function definition()
  {
    SemesterType::factory()->create();
    return [
      'name' => $this->faker->name,
      'abbreviation'=>$this->faker->randomLetter,
      'active' => $this->faker->boolean,
      'semester_type_id' => 1
    ];
  }
}
