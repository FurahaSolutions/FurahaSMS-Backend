<?php

namespace Okotieno\LMS\Tests\Unit;

use Okotieno\LMS\Models\BookIssue;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;

class LibraryBookItemTest extends TestCase
{
  /**
   * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
   */
  private $libraryBookItem;

  protected function setUp(): void
  {
    parent::setUp();
    $this->libraryBookItem = LibraryBookItem::factory()->make();
  }

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
    $url = 'api/library-books/library-book-items?book_ref=' . $bookItemIssued->libraryBookItem->ref . '&borrowed_only=1';
    $this->actingAs($this->user, 'api')->getJson($url)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'title', 'ref']])
      ->assertJsonFragment(['title' => $bookItemIssued->libraryBookItem->libraryBook->title])
      ->assertJsonMissing(['title' => $bookItemNotIssued->title]);
  }

  /**
   * POST /api/library-books/library-book-items
   * @group library
   * @group library-book-item
   * @test
   */
  public function unauthenticated_users_cannot_create_books()
  {
    $this->postJson('api/library-books/library-book-items', $this->libraryBookItem->toArray())
      ->assertStatus(401);
  }

  /**
   * POST /api/library-books/library-book-items
   * @group library
   * @group library-book-item
   * @test
   */
  public function authenticated_users_without_permission_cannot_create_books()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('api/library-books/library-book-items', $this->libraryBookItem->toArray())
      ->assertStatus(403);
  }

  /**
   * POST /api/library-books/library-book-items
   * @group library
   * @group library-book-item
   * @test
   */
  public function authenticated_users_with_permission_can_create_book_item()
  {
    Permission::factory()->state(['name' => 'create library book item'])->create();
    $this->user->givePermissionTo('create library book item');
    $this->actingAs($this->user, 'api')
      ->postJson('api/library-books/library-book-items', $this->libraryBookItem->toArray())
      ->assertStatus(201)
      ->assertJsonStructure([
        'message',
        'saved',
        'data' => ['id', 'ref']
      ]);
  }

  /**
   * PATCH /api/library-books/library-book-items/:library-book-item
   * @group library
   * @group library-book-item
   * @test
   */
  public function unauthenticated_users_cannot_update_books()
  {
    $libraryBookItem = LibraryBookItem::factory()->create();
    $this->patchJson("api/library-books/library-book-items/{$libraryBookItem->id}", $this->libraryBookItem->toArray())
      ->assertStatus(401);
  }

  /**
   * PATCH /api/library-books/library-book-items
   * @group library
   * @group library-book-item
   * @test
   */
  public function authenticated_users_without_permission_cannot_update_books()
  {
    $libraryBookItem = LibraryBookItem::factory()->create();
    $this->actingAs($this->user, 'api')
      ->patchJson("api/library-books/library-book-items/{$libraryBookItem->id}", $this->libraryBookItem->toArray())
      ->assertStatus(403);
  }

  /**
   * PATCH /api/library-books/library-book-items
   * @group library
   * @group library-book-item
   * @test
   */
  public function authenticated_users_with_permission_can_update_book_item()
  {
    $libraryBookItem = LibraryBookItem::factory()->create();
    Permission::factory()->state(['name' => 'update library book item'])->create();
    $this->user->givePermissionTo('update library book item');
    $this->actingAs($this->user, 'api')
      ->patchJson("api/library-books/library-book-items/{$libraryBookItem->id}", $this->libraryBookItem->toArray())
      ->assertStatus(200)
      ->assertJsonStructure([
        'message',
        'saved'
      ]);
  }

  /**
   * DELETE /api/library-books/library-book-items/:library-book-item
   * @group library
   * @group library-book-item
   * @test
   */
  public function unauthenticated_users_cannot_delete_book_item()
  {
    $libraryBookItem = LibraryBookItem::factory()->create();
    $this->deleteJson("api/library-books/library-book-items/{$libraryBookItem->id}", $this->libraryBookItem->toArray())
      ->assertStatus(401);
  }

  /**
   * DELETE /api/library-books/library-book-items/:library-book-item
   * @group library
   * @group library-book-item
   * @test
   */
  public function authenticated_users_without_permission_cannot_delete_book_item()
  {
    $libraryBookItem = LibraryBookItem::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson("api/library-books/library-book-items/{$libraryBookItem->id}", $this->libraryBookItem->toArray())
      ->assertStatus(403);
  }

  /**
   * DELETE /api/library-books/library-book-items/:library-book-item
   * @group library
   * @group library-book-item
   * @test
   */
  public function authenticated_users_with_permission_can_delete_book_item()
  {
    $libraryBookItem = LibraryBookItem::factory()->create();
    Permission::factory()->state(['name' => 'delete library book item'])->create();
    $this->user->givePermissionTo('delete library book item');
    $this->actingAs($this->user, 'api')
      ->deleteJson("api/library-books/library-book-items/{$libraryBookItem->id}", $this->libraryBookItem->toArray())
      ->assertStatus(200)
      ->assertJsonStructure([
        'message',
        'saved'
      ]);
  }
}
