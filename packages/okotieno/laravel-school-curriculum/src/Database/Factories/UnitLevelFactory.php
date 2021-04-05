<?php


namespace Okotieno\SchoolCurriculum\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolCurriculum\Models\Unit;
use Okotieno\SchoolCurriculum\Models\UnitLevel;

class UnitLevelFactory extends Factory
{
  protected $model = UnitLevel::class;

  public function definition()
  {
    $unit = Unit::factory()->create();
    return [
      'name' => $this->faker->name,
      'level' => $this->faker->numberBetween(0, 10),
      'unit_id' => $unit->id
    ];
  }
}
