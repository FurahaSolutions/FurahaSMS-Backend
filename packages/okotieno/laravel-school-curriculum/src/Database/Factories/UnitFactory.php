<?php


namespace Okotieno\SchoolCurriculum\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolCurriculum\Models\Unit;
use Okotieno\SchoolCurriculum\Models\UnitCategory;

class UnitFactory extends Factory
{
  protected $model = Unit::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'abbreviation'=>$this->faker->randomLetter,
      'active' => $this->faker->boolean,
      'default' => $this->faker->boolean,
      'unit_category_id' => UnitCategory::factory()->create()->id,
      'description' => $this->faker->sentence,
    ];
  }
}
