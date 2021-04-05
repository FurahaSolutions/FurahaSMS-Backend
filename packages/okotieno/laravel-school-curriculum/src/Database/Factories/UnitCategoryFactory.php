<?php


namespace Okotieno\SchoolCurriculum\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolCurriculum\Models\Unit;
use Okotieno\SchoolCurriculum\Models\UnitCategory;

class UnitCategoryFactory extends Factory
{
  protected $model = UnitCategory::class;

  public function definition()
  {

    return [
      'name' => $this->faker->name,
      'active' => $this->faker->boolean,
      'description' => $this->faker->sentence,
    ];
  }
}
