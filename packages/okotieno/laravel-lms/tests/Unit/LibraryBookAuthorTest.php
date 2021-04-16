<?php

namespace Okotieno\LMS\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\LMS\Models\LibraryBookAuthor;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;


class LibraryBookAuthorTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $bookAuthor;

  /**
   * GET /api/library-book-author
   * @group library
   * @group library-book-author
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_book_authors()
  {
    $this->getJson('/api/library-book-author', $this->bookAuthor)
      ->assertStatus(401);

  }

  /**
   * GET /api/library-book-author
   * @group library
   * @group library-book-author
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_authors()
  {
    LibraryBookAuthor::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-book-author', $this->bookAuthor)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/library-book-author/:id
   * @group library
   * @group library-book-author
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_book_author()
  {
    $author = LibraryBookAuthor::factory()->create();
    $this->getJson('/api/library-book-author/' . $author->id, $this->bookAuthor)
      ->assertStatus(401);

  }

  /**
   * GET /api/library-book-author/:id
   * @group library
   * @group library-book-author
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_author()
  {
    $author = LibraryBookAuthor::factory()->create();
    LibraryBookAuthor::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-book-author/' . $author->id, $this->bookAuthor)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }

  /**
   * POST /api/library-book-author
   * @group library
   * @group library-book-author
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_book_author()
  {
    $this->postJson('/api/library-book-author', $this->bookAuthor)
      ->assertStatus(401);

  }

  /**
   * POST /api/library-book-author
   * @group library
   * @group library-book-author
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_book_author()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/library-book-author', $this->bookAuthor)
      ->assertStatus(403);
  }

  /**
   * POST /api/library-book-author
   * @group library
   * @group library-book-author
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_book_author()
  {
    Permission::factory()->state(['name' => 'create library book author'])->create();
    $this->user->givePermissionTo('create library book author');
    $response = $this->actingAs($this->user, 'api')
      ->postJson('/api/library-book-author', $this->bookAuthor)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $response->assertStatus(201);
  }

  /**
   * POST /api/library-book-author
   * @group library
   * @group library-book-author
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->bookAuthor['name'] = '';
    Permission::factory()->state(['name' => 'create library book author'])->create();
    $this->user->givePermissionTo('create library book author');
    $this->actingAs($this->user, 'api')->postJson('/api/library-book-author', $this->bookAuthor)
      ->assertStatus(422);
  }

  /**
   * POST /api/library-book-author
   * @group library
   * @group library-book-author
   * @test
   * @group post-request
   * @return void
   */
  public function library_book_author_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create library book author'])->create();
    $this->user->givePermissionTo('create library book author');
    $this->actingAs($this->user, 'api')->postJson('/api/library-book-author', $this->bookAuthor)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $bookAuthor = LibraryBookAuthor::where('name', $this->bookAuthor['name'])
      ->where('name', $this->bookAuthor['name'])->first();
    $this->assertNotNull($bookAuthor);
  }

  /**
   * PATCH /api/library-book-author/{id}
   * @group library
   * @group library-book-author
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_book_author()
  {
    $bookAuthor = LibraryBookAuthor::factory()->create();
    $bookAuthorUpdate = LibraryBookAuthor::factory()->make()->toArray();
    $res = $this->patchJson('/api/library-book-author/' . $bookAuthor->id, $bookAuthorUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/library-book-author/{id}
   * @group library
   * @group library-book-author
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_book_author()
  {
    $bookAuthor = LibraryBookAuthor::factory()->create();
    $bookAuthorUpdate = LibraryBookAuthor::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-book-author/' . $bookAuthor->id, $bookAuthorUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/library-book-author/{id}
   * @group library
   * @group library-book-author
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_book_author()
  {
    Permission::factory()->state(['name' => 'update library book author'])->create();
    $this->user->givePermissionTo('update library book author');

    $bookAuthor = LibraryBookAuthor::factory()->create();
    $bookAuthorUpdate = LibraryBookAuthor::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-book-author/' . $bookAuthor->id, $bookAuthorUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/library-book-author/{id}
   * @group library
   * @group library-book-author
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update library book author'])->create();
    $this->user->givePermissionTo('update library book author');
    $bookAuthor = LibraryBookAuthor::factory()->create();
    $bookAuthorUpdate = LibraryBookAuthor::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-book-author/' . $bookAuthor->id, $bookAuthorUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/library-book-author/{id}
   * @group library
   * @group library-book-author
   * @test
   * @group patch-request
   * @return void
   */
  public function library_book_author_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update library book author'])->create();
    $this->user->givePermissionTo('update library book author');
    $bookAuthor = LibraryBookAuthor::factory()->create();
    $bookAuthorUpdate = LibraryBookAuthor::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-book-author/' . $bookAuthor->id, $bookAuthorUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE/api/library-book-author/{id}
   * @group library
   * @group library-book-author
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_book_author()
  {
    $bookAuthor = LibraryBookAuthor::factory()->create();
    $this->deleteJson('/api/library-book-author/' . $bookAuthor->id)
      ->assertStatus(401);

  }

  /**
   * DELETE/api/library-book-author/{id}
   * @group library
   * @group library-book-author
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_book_author()
  {
    $bookAuthor = LibraryBookAuthor::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-book-author/' . $bookAuthor->id)
      ->assertStatus(403);
  }

  /**
   * DELETE/api/library-book-author/{id}
   * @group library
   * @group library-book-author
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_book_author()
  {
    Permission::factory()->state(['name' => 'delete library book author'])->create();
    $this->user->givePermissionTo('delete library book author');
    $bookAuthor = LibraryBookAuthor::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-book-author/' . $bookAuthor->id)
      ->assertStatus(200);
  }

  /**
   * DELETE/api/library-book-author/{id}
   * @group library
   * @group library-book-author
   * @test
   * @group delete-request
   * @return void
   */
  public function library_book_author_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete library book author'])->create();
    $this->user->givePermissionTo('delete library book author');
    $bookAuthor = LibraryBookAuthor::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-book-author/' . $bookAuthor->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(LibraryBookAuthor::find($bookAuthor->id));
  }

  protected function setUp(): void
  {
    parent::setUp();
    $this->bookAuthor = LibraryBookAuthor::factory()->make()->toArray();
  }
}



