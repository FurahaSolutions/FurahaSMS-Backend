<?php


namespace Okotieno\SchoolCurriculum\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\ClassLevelCategory;

class ClassLevelFactory extends Factory
{
  protected $model = ClassLevel::class;

  public function definition()
  {
    $classLevelCategory = ClassLevelCategory::factory()->create();
    return [
      'name' => $this->faker->name,
      'class_level_category_id' => $classLevelCategory->id,
      'active' => $this->faker->boolean,
      'description' => $this->faker->sentence,
      'abbreviation' => $this->faker->randomLetter
    ];
  }
}
