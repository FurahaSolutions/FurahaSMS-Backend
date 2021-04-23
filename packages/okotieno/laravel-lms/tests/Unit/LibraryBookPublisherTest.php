<?php

namespace Okotieno\LMS\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\LMS\Models\LibraryBookPublisher;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;


class LibraryBookPublisherTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $bookPublisher;

  /**
   * GET /api/library-books/publishers
   * @group library
   * @group library-book-publisher
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_book_publishers()
  {
    $this->getJson('/api/library-books/publishers', $this->bookPublisher)
      ->assertStatus(401);

  }

  /**
   * GET /api/library-books/publishers
   * @group library
   * @group library-book-publisher
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_publishers()
  {
    LibraryBookPublisher::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-books/publishers', $this->bookPublisher)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/library-books/publishers/:id
   * @group library
   * @group library-book-publisher
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_book_publisher()
  {
    $publisher = LibraryBookPublisher::factory()->create();
    $this->getJson('/api/library-books/publishers/' . $publisher->id, $this->bookPublisher)
      ->assertStatus(401);

  }

  /**
   * GET /api/library-books/publishers/:id
   * @group library
   * @group library-book-publisher
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_publisher()
  {
    $publisher = LibraryBookPublisher::factory()->create();
    LibraryBookPublisher::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-books/publishers/' . $publisher->id, $this->bookPublisher)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }

  /**
   * POST /api/library-books/publishers
   * @group library
   * @group library-book-publisher
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_book_publisher()
  {
    $this->postJson('/api/library-books/publishers', $this->bookPublisher)
      ->assertStatus(401);

  }

  /**
   * POST /api/library-books/publishers
   * @group library
   * @group library-book-publisher
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_book_publisher()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/library-books/publishers', $this->bookPublisher)
      ->assertStatus(403);
  }

  /**
   * POST /api/library-books/publishers
   * @group library
   * @group library-book-publisher
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_book_publisher()
  {
    Permission::factory()->state(['name' => 'create library book publisher'])->create();
    $this->user->givePermissionTo('create library book publisher');
    $response = $this->actingAs($this->user, 'api')
      ->postJson('/api/library-books/publishers', $this->bookPublisher)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * POST /api/library-books/publishers
   * @group library
   * @group library-book-publisher
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->bookPublisher['name'] = '';
    Permission::factory()->state(['name' => 'create library book publisher'])->create();
    $this->user->givePermissionTo('create library book publisher');
    $this->actingAs($this->user, 'api')->postJson('/api/library-books/publishers', $this->bookPublisher)
      ->assertStatus(422);
  }

  /**
   * POST /api/library-books/publishers
   * @group library
   * @group library-book-publisher
   * @test
   * @group post-request
   * @return void
   */
  public function library_book_publisher_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create library book publisher'])->create();
    $this->user->givePermissionTo('create library book publisher');
    $this->actingAs($this->user, 'api')->postJson('/api/library-books/publishers', $this->bookPublisher)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $bookPublisher = LibraryBookPublisher::where('name', $this->bookPublisher['name'])
      ->where('name', $this->bookPublisher['name'])->first();
    $this->assertNotNull($bookPublisher);
  }

  /**
   * PATCH /api/library-books/publishers/{id}
   * @group library
   * @group library-book-publisher
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_book_publisher()
  {
    $bookPublisher = LibraryBookPublisher::factory()->create();
    $bookPublisherUpdate = LibraryBookPublisher::factory()->make()->toArray();
    $res = $this->patchJson('/api/library-books/publishers/' . $bookPublisher->id, $bookPublisherUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/library-books/publishers/{id}
   * @group library
   * @group library-book-publisher
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_book_publisher()
  {
    $bookPublisher = LibraryBookPublisher::factory()->create();
    $bookPublisherUpdate = LibraryBookPublisher::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/publishers/' . $bookPublisher->id, $bookPublisherUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/library-books/publishers/{id}
   * @group library
   * @group library-book-publisher
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_book_publisher()
  {
    Permission::factory()->state(['name' => 'update library book publisher'])->create();
    $this->user->givePermissionTo('update library book publisher');

    $bookPublisher = LibraryBookPublisher::factory()->create();
    $bookPublisherUpdate = LibraryBookPublisher::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/publishers/' . $bookPublisher->id, $bookPublisherUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/library-books/publishers/{id}
   * @group library
   * @group library-book-publisher
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update library book publisher'])->create();
    $this->user->givePermissionTo('update library book publisher');
    $bookPublisher = LibraryBookPublisher::factory()->create();
    $bookPublisherUpdate = LibraryBookPublisher::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/publishers/' . $bookPublisher->id, $bookPublisherUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/library-books/publishers/{id}
   * @group library
   * @group library-book-publisher
   * @test
   * @group patch-request
   * @return void
   */
  public function library_book_publisher_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update library book publisher'])->create();
    $this->user->givePermissionTo('update library book publisher');
    $bookPublisher = LibraryBookPublisher::factory()->create();
    $bookPublisherUpdate = LibraryBookPublisher::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/publishers/' . $bookPublisher->id, $bookPublisherUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE/api/library-books/publishers/{id}
   * @group library
   * @group library-book-publisher
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_book_publisher()
  {
    $bookPublisher = LibraryBookPublisher::factory()->create();
    $this->deleteJson('/api/library-books/publishers/' . $bookPublisher->id)
      ->assertStatus(401);

  }

  /**
   * DELETE/api/library-books/publishers/{id}
   * @group library
   * @group library-book-publisher
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_book_publisher()
  {
    $bookPublisher = LibraryBookPublisher::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-books/publishers/' . $bookPublisher->id)
      ->assertStatus(403);
  }

  /**
   * DELETE/api/library-books/publishers/{id}
   * @group library
   * @group library-book-publisher
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_book_publisher()
  {
    Permission::factory()->state(['name' => 'delete library book publisher'])->create();
    $this->user->givePermissionTo('delete library book publisher');
    $bookPublisher = LibraryBookPublisher::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-books/publishers/' . $bookPublisher->id)
      ->assertStatus(200);
  }

  /**
   * DELETE/api/library-books/publishers/{id}
   * @group library
   * @group library-book-publisher
   * @test
   * @group delete-request
   * @return void
   */
  public function library_book_publisher_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete library book publisher'])->create();
    $this->user->givePermissionTo('delete library book publisher');
    $bookPublisher = LibraryBookPublisher::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-books/publishers/' . $bookPublisher->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(LibraryBookPublisher::find($bookPublisher->id));
  }

  protected function setUp(): void
  {
    parent::setUp();
    $this->bookPublisher = LibraryBookPublisher::factory()->make()->toArray();
  }
}



