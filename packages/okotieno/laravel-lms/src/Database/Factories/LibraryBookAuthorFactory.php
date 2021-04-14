<?php


namespace Okotieno\LMS\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\LMS\Models\LibraryBookAuthor;

class LibraryBookAuthorFactory extends Factory
{
  protected $model = LibraryBookAuthor::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name
    ];
  }
}
