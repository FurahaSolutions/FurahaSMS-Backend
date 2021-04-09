<?php


namespace Okotieno\TimeTable\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\TimeTable\Models\TimeTableTiming;

class TimeTableTimingFactory extends Factory
{
  protected $model = TimeTableTiming::class;

  public function definition()
  {
    return [
      'start' => $this->faker->time,
      'end' => $this->faker->time
    ];
  }
}
