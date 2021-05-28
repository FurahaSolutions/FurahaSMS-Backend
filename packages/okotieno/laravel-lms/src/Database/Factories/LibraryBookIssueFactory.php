<?php


namespace Okotieno\LMS\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\LMS\Models\BookIssue;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\LMS\Models\LibraryUser;

class LibraryBookIssueFactory extends Factory
{

  protected $model = BookIssue::class;

  public function definition()
  {
    return [
      'library_book_item_id' => LibraryBookItem::factory()->create()->id,
      'library_user_id' => LibraryUser::factory()->create()->id,
      'issue_date' => $this->faker->date()
    ];
  }
}
