<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Okotieno\LMS\Models\LibraryBook;
use Okotieno\LMS\Models\LibraryBookAuthor;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\LMS\Models\LibraryBookPublisher;
use Okotieno\LMS\Models\LibraryBookTag;

class LibrarySeeder extends Seeder
{
  /**
   * The current Faker instance.
   *
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Create a new seeder instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->faker = Factory::create();
  }

  public function run()
  {
    $authors = LibraryBookAuthor::factory()->count(10)->create()->pluck('id')->toArray();
    $publishers = LibraryBookPublisher::factory()->count(5)->create()->pluck('id')->toArray();
    $tags = LibraryBookTag::factory()->count(15)->create()->pluck('id')->toArray();
    $noOfAuthors = $this->faker->numberBetween(1, 3);
    $noOfPublishers = $this->faker->numberBetween(1, 2);
    $noOfTags = $this->faker->numberBetween(2, 5);
    $books = LibraryBook::factory()->count(20)->create();

    foreach ($books as $book) {

      $book->libraryBookPublishers()->attach(array_rand($publishers, $noOfPublishers));
      $book->libraryBookAuthors()->attach(array_rand($authors, $noOfAuthors));
      $book->libraryBookTags()->attach(array_rand($tags, $noOfTags));
      $noOfBookItems = $this->faker->numberBetween(1, 6);
      LibraryBookItem::factory()
        ->count($noOfBookItems)
        ->state(['library_book_id' => $book->id])
        ->create();

    }
  }
}
