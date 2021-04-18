<?php


namespace Okotieno\SchoolCurriculum\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolCurriculum\Models\SemesterType;
use Okotieno\SchoolCurriculum\Models\Unit;
use Okotieno\SchoolCurriculum\Models\UnitCategory;

class SemesterTypeFactory extends Factory
{
  protected $model = SemesterType::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'active' => $this->faker->boolean,
      'default' => $this->faker->boolean
    ];
  }
}
