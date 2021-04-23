<?php


namespace Okotieno\LMS\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\LMS\Models\LibraryClass;
use Okotieno\LMS\Models\LibraryClassification;

class LibraryClassFactory extends Factory
{
  protected $model = LibraryClass::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'library_classification_id' => LibraryClassification::factory()->create()->id,
      'library_class_id' => null,
      'class' => $this->faker->name,
    ];
  }
}
