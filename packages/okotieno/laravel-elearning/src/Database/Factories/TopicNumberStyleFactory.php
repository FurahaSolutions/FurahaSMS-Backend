<?php


namespace Okotieno\ELearning\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\ELearning\Models\TopicNumberStyle;

class TopicNumberStyleFactory extends Factory
{

  protected $model = TopicNumberStyle::class;


  public function definition()
  {
    return [
      'name' => $this->faker->colorName,
      'default' => $this->faker->boolean,
      'active' => $this->faker->boolean
    ];
  }
}
