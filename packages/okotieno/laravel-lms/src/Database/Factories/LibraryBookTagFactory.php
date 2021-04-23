<?php


namespace Okotieno\LMS\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\LMS\Models\LibraryBookTag;

class LibraryBookTagFactory extends Factory
{
  protected $model = LibraryBookTag::class;
  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'active' => $this->faker->boolean
    ];
  }
}
