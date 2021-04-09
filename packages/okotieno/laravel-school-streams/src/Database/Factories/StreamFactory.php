<?php


namespace Okotieno\SchoolStreams\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolStreams\Models\Stream;

class StreamFactory extends Factory
{
  protected $model = Stream::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'active' => $this->faker->boolean,
      'abbreviation' => $this->faker->randomLetter,
      'associated_color' => $this->faker->colorName
    ];
  }
}
