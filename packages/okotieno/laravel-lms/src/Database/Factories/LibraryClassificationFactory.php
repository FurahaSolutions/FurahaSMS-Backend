<?php


namespace Okotieno\LMS\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\LMS\Models\LibraryClassification;

class LibraryClassificationFactory extends Factory
{
  protected $model = LibraryClassification::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'abbreviation' => $this->faker->randomLetter
    ];
  }
}
