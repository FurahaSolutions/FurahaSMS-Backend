<?php


namespace Okotieno\LMS\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\LMS\Models\LibraryBook;
use Okotieno\LMS\Models\LibraryBookItem;

class LibraryBookItemFactory extends Factory
{

  protected $model = LibraryBookItem::class;
  public function definition()
  {
    return [
      'ref' => $this->faker->slug(3),
      'library_book_id' => LibraryBook::factory()->create()->id,
      'procurement_date' => $this->faker->date()
    ];
  }
}
