<?php

namespace Okotieno\AcademicYear\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\AcademicYear\Models\Holiday;

class HolidayFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Holiday::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'occurs_on' => $this->faker->date(),
    ];
  }
}
