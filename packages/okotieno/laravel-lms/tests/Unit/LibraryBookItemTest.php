<?php

namespace Okotieno\LMS\Tests\Unit;

use Okotieno\LMS\Models\BookIssue;
use Okotieno\LMS\Models\LibraryBookItem;
use Tests\TestCase;

class LibraryBookItemTest extends TestCase
{
  /**
   * GET /api/library-books/library-book-items
   * @group library
   * @group library-book-item
   * @test
   */
  public function unauthenticated_users_cannot_get_books_by_book_ref()
  {
    $this->getJson('api/library-books/library-book-items')
      ->assertStatus(401);
  }

  /**
   * GET /api/library-books/library-book-items
   * @group library
   * @group library-book-item
   * @test
   */
  public function authenticated_users_can_get_books_by_book_ref()
  {
    $bookItem = LibraryBookItem::factory()->create();
    $url = 'api/library-books/library-book-items?book_ref=' . $bookItem->ref;
    $this->actingAs($this->user, 'api')->getJson($url)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'title', 'ref']])
      ->assertJsonFragment(['title' => $bookItem->libraryBook->title]);
  }

  /**
   * GET /api/library-books/library-book-items
   * @group library
   * @group library-book-item
   * @test
   */
  public function authenticated_users_can_get_books_by_book_ref_only_borrowed()
  {
    $bookItemNotIssued = LibraryBookItem::factory()->create();
    $bookItemIssued = BookIssue::factory()->create();
    $url = 'api/library-books/library-book-items?book_ref=' . $bookItemIssued->libraryBookItem->ref.'&borrowed_only=1';
    $this->actingAs($this->user, 'api')->getJson($url)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'title', 'ref']])
      ->assertJsonFragment(['title' => $bookItemIssued->libraryBookItem->libraryBook->title])
      ->assertJsonMissing(['title' => $bookItemNotIssued->title]);
  }
}
