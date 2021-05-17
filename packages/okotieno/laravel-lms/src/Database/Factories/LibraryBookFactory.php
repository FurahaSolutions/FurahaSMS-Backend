<?php


namespace Okotieno\LMS\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\LMS\Models\LibraryBook;

class LibraryBookFactory extends Factory
{
  protected $model = LibraryBook::class;

  public function definition()
  {
    return [
      'title' => $this->faker->sentence($this->faker->numberBetween(3,6)),
      'ISBN' => $this->faker->numberBetween(10000000000, 9999999999)
    ];
  }
}
