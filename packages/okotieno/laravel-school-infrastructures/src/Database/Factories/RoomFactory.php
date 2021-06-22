<?php


namespace Okotieno\SchoolInfrastructure\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolInfrastructure\Models\Room;

class RoomFactory extends Factory
{
  protected $model = Room::class;

  public function definition()
  {
    return [
      'name' => $this->faker->buildingNumber,
      'abbreviation' => $this->faker->buildingNumber,
      'width' => $this->faker->numberBetween(20,30),
      'length' => $this->faker->numberBetween(30,50),
      'students_capacity' => $this->faker->numberBetween(50, 100),
      'is_classroom' => $this->faker->boolean(80)
    ];
  }
}
