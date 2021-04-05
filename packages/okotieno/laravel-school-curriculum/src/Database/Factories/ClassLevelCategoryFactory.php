<?php


namespace Okotieno\SchoolCurriculum\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolCurriculum\Models\ClassLevelCategory;

class ClassLevelCategoryFactory extends Factory
{
  protected $model = ClassLevelCategory::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'active' => $this->faker->boolean,
      'description' => $this->faker->sentence
    ];
  }
}
