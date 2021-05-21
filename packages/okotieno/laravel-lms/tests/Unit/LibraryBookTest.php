<?php

namespace Okotieno\LMS\Tests\Unit;

use Carbon\Carbon;
use Okotieno\LMS\Models\LibraryBook;
use Okotieno\LMS\Models\LibraryBookAuthor;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\LMS\Models\LibraryBookPublisher;
use Okotieno\LMS\Models\LibraryBookTag;
use Okotieno\LMS\Models\LibraryClass;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;


class LibraryBookTest extends TestCase
{

  private $book;

  protected function setUp(): void
  {
    parent::setUp();
    $this->book = LibraryBook::factory([
      'category' => LibraryClass::factory()->create()->id,
      'authors' => LibraryBookAuthor::factory()->count(2)->create()->pluck('id'),
      'publishers' => LibraryBookPublisher::factory()->count(2)->create()->pluck('id'),
      'tags' => LibraryBookTag::factory()->count(2)->create()->pluck('id'),
    ])
      ->make()->toArray();
  }

  /**
   * GET /api/library-books
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_books()
  {
    $this->getJson('/api/library-books')
      ->assertStatus(401);

  }

  /**
   * GET /api/library-books
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_books()
  {
    LibraryBook::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-books')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'title']]);

  }

  /**
   * GET /api/library-books
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_by_book_id()
  {
    $libraryBooks = LibraryBook::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-books?book_id=' . $libraryBooks[2]->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'title'])
      ->assertJsonFragment(['title' => $libraryBooks[2]->title]);

  }

  /**
   * GET /api/library-books
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_by_book_title()
  {
    $libraryBooks = LibraryBook::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-books?title=' . $libraryBooks[2]->title)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'title']])
      ->assertJsonFragment(['id' => $libraryBooks[2]->id]);

  }

  /**
   * GET /api/library-books
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_by_book_author()
  {
    $libraryBook = LibraryBook::factory()->create();
    $libraryBookAuthor = LibraryBookAuthor::factory()->create();
    $libraryBook->libraryBookAuthors()->save($libraryBookAuthor);
    $this->actingAs($this->user, 'api')->getJson('/api/library-books?author=' . $libraryBookAuthor->name)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'title']])
      ->assertJsonFragment(['id' => $libraryBook->id]);

  }

  /**
   * GET /api/library-books
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_by_book_publisher()
  {
    $libraryBook = LibraryBook::factory()->create();
    $libraryBookPublisher = LibraryBookPublisher::factory()->create();
    $libraryBook->libraryBookPublishers()->save($libraryBookPublisher);
    $this->actingAs($this->user, 'api')->getJson('/api/library-books?publisher=' . $libraryBookPublisher->name)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'title']])
      ->assertJsonFragment(['id' => $libraryBook->id]);

  }

  /**
   * GET /api/library-books?title=:title
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_with()
  {
    $libraryBook = LibraryBook::factory()->create();
    $libraryBookItems = LibraryBookItem::factory()
      ->count(3)->state(['library_book_id' => $libraryBook])->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-books')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'title', 'book_items']])
      ->assertJsonFragment(['id' => $libraryBook->id])
      ->assertJsonFragment(['ref' => $libraryBookItems[0]->ref])
      ->assertJsonFragment(['title' => $libraryBook->title]);

  }

  /**
   * GET /api/library-books
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_all_their_borrowed_books()
  {
    $libraryBookItem = LibraryBookItem::factory()->create();
    $this->user->libraryUser()->create();
    $this->user
      ->libraryUser
      ->libraryBookItems()
      ->save($libraryBookItem, ['issue_date' => Carbon::now()]);
    $this->actingAs($this->user, 'api')
      ->getJson('/api/library-books?my-account=1')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'title']])
      ->assertJsonCount(1)
      ->assertJsonFragment(['id' => $libraryBookItem->libraryBook->id]);

  }

  /**
   * GET /api/library-books/:id
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_book()
  {
    $book = LibraryBook::factory()->create();
    $this->getJson('/api/library-books/' . $book->id, $this->book)
      ->assertStatus(401);

  }

  /**
   * GET /api/library-books/:id
   * @group library
   * @group library-book
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book()
  {
    $book = LibraryBook::factory()->create();
    LibraryBook::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-books/' . $book->id, $this->book)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'title']);

  }

  /**
   * POST /api/library-books
   * @group library
   * @group library-book
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_book()
  {
    $this->postJson('/api/library-books', $this->book)
      ->assertStatus(401);

  }

  /**
   * POST /api/library-books
   * @group library
   * @group library-book
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_book()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/library-books', $this->book)
      ->assertStatus(403);
  }

  /**
   * POST /api/library-books
   * @group library
   * @group library-book
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_book()
  {
    Permission::factory()->state(['name' => 'create library book'])->create();
    $this->user->givePermissionTo('create library book');

    $this->actingAs($this->user, 'api')
      ->postJson('/api/library-books', $this->book)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'title']]);
  }

  /**
   * POST /api/library-books
   * @group library
   * @group library-book
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_title_not_provided()
  {
    $this->book['title'] = '';
    Permission::factory()->state(['name' => 'create library book'])->create();
    $this->user->givePermissionTo('create library book');
    $this->actingAs($this->user, 'api')->postJson('/api/library-books', $this->book)
      ->assertStatus(422);
  }

  /**
   * POST /api/library-books
   * @group library
   * @group library-book
   * @test
   * @group post-request
   * @return void
   */
  public function library_book_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create library book'])->create();
    $this->user->givePermissionTo('create library book');
    $this->actingAs($this->user, 'api')->postJson('/api/library-books', $this->book)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'title']]);
    $book = LibraryBook::where('title', $this->book['title'])->first();
    $this->assertNotNull($book);
  }

  /**
   * PATCH /api/library-books/{id}
   * @group library
   * @group library-book
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_book()
  {
    $book = LibraryBook::factory()->create();
    $bookUpdate = LibraryBook::factory()->make()->toArray();
    $res = $this->patchJson('/api/library-books/' . $book->id, $bookUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/library-books/{id}
   * @group library
   * @group library-book
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_book()
  {
    $book = LibraryBook::factory()->create();
    $bookUpdate = LibraryBook::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/' . $book->id, $bookUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/library-books/{id}
   * @group library
   * @group library-book
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_book()
  {
    Permission::factory()->state(['name' => 'update library book'])->create();
    $this->user->givePermissionTo('update library book');

    $book = LibraryBook::factory()->create();
    $bookUpdate = LibraryBook::factory()->state([
      'category' => LibraryClass::factory()->create()->id,
    ])->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/' . $book->id, $bookUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/library-books/{id}
   * @group library
   * @group library-book
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_title_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update library book'])->create();
    $this->user->givePermissionTo('update library book');
    $book = LibraryBook::factory()->create();
    $bookUpdate = LibraryBook::factory()->state(['title' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/' . $book->id, $bookUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/library-books/{id}
   * @group library
   * @group library-book
   * @test
   * @group patch-request
   * @return void
   */
  public function library_book_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update library book'])->create();
    $this->user->givePermissionTo('update library book');
    $book = LibraryBook::factory()->create();
    $bookUpdate = LibraryBook::factory()->state([
      'category' => LibraryClass::factory()->create()->id,
    ])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/' . $book->id, $bookUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'title']]);
  }

  /**
   * DELETE/api/library-books/{id}
   * @group library
   * @group library-book
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_book()
  {
    $book = LibraryBook::factory()->create();
    $this->deleteJson('/api/library-books/' . $book->id)
      ->assertStatus(401);

  }

  /**
   * DELETE/api/library-books/{id}
   * @group library
   * @group library-book
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_book()
  {
    $book = LibraryBook::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-books/' . $book->id)
      ->assertStatus(403);
  }

  /**
   * DELETE/api/library-books/{id}
   * @group library
   * @group library-book
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_book()
  {
    Permission::factory()->state(['name' => 'delete library book'])->create();
    $this->user->givePermissionTo('delete library book');
    $book = LibraryBook::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-books/' . $book->id)
      ->assertStatus(200);
  }

  /**
   * DELETE/api/library-books/{id}
   * @group library
   * @group library-book
   * @test
   * @group delete-request
   * @return void
   */
  public function library_book_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete library book'])->create();
    $this->user->givePermissionTo('delete library book');
    $book = LibraryBook::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-books/' . $book->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(LibraryBook::find($book->id));
  }

}



