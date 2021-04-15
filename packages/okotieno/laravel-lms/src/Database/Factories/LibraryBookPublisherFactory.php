<?php


namespace Okotieno\LMS\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\LMS\Models\LibraryBookPublisher;

class LibraryBookPublisherFactory extends Factory
{
  protected $model = LibraryBookPublisher::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'biography' => $this->faker->paragraph
    ];
  }
}
