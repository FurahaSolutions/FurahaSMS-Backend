<?php


namespace Okotieno\TimeTable\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\TimeTable\Models\WeekDay;

class WeekDayFactory extends Factory
{
  protected $model = WeekDay::class;
  public function definition()
  {
    return [
      'name' => $this->faker->dayOfWeek,
      'abbreviation' => $this->faker->dayOfWeek,
      'active' => $this->faker->boolean
    ];
  }
}
