<?php

namespace Okotieno\LMS\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\LMS\Models\LibraryBookTag;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;


class LibraryBookTagTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $bookTag;

  /**
   * GET /api/library-books/tags
   * @group library
   * @group library-book-tag
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_book_tags()
  {
    $this->getJson('/api/library-books/tags', $this->bookTag)
      ->assertStatus(401);

  }

  /**
   * GET /api/library-books/tags
   * @group library
   * @group library-book-tag
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_tags()
  {
    LibraryBookTag::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-books/tags', $this->bookTag)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/library-books/tags/:id
   * @group library
   * @group library-book-tag
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_book_tag()
  {
    $tag = LibraryBookTag::factory()->create();
    $this->getJson('/api/library-books/tags/' . $tag->id, $this->bookTag)
      ->assertStatus(401);

  }

  /**
   * GET /api/library-books/tags/:id
   * @group library
   * @group library-book-tag
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_book_tag()
  {
    $tag = LibraryBookTag::factory()->create();
    LibraryBookTag::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/library-books/tags/' . $tag->id, $this->bookTag)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }

  /**
   * POST /api/library-books/tags
   * @group library
   * @group library-book-tag
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_book_tag()
  {
    $this->postJson('/api/library-books/tags', $this->bookTag)
      ->assertStatus(401);

  }

  /**
   * POST /api/library-books/tags
   * @group library
   * @group library-book-tag
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_book_tag()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/library-books/tags', $this->bookTag)
      ->assertStatus(403);
  }

  /**
   * POST /api/library-books/tags
   * @group library
   * @group library-book-tag
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_book_tag()
  {
    Permission::factory()->state(['name' => 'create library book tag'])->create();
    $this->user->givePermissionTo('create library book tag');
    $response = $this->actingAs($this->user, 'api')
      ->postJson('/api/library-books/tags', $this->bookTag)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $response->assertStatus(201);
  }

  /**
   * POST /api/library-books/tags
   * @group library
   * @group library-book-tag
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->bookTag['name'] = '';
    Permission::factory()->state(['name' => 'create library book tag'])->create();
    $this->user->givePermissionTo('create library book tag');
    $this->actingAs($this->user, 'api')->postJson('/api/library-books/tags', $this->bookTag)
      ->assertStatus(422);
  }

  /**
   * POST /api/library-books/tags
   * @group library
   * @group library-book-tag
   * @test
   * @group post-request
   * @return void
   */
  public function library_book_tag_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create library book tag'])->create();
    $this->user->givePermissionTo('create library book tag');
    $this->actingAs($this->user, 'api')->postJson('/api/library-books/tags', $this->bookTag)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $bookTag = LibraryBookTag::where('name', $this->bookTag['name'])
      ->where('name', $this->bookTag['name'])->first();
    $this->assertNotNull($bookTag);
  }

  /**
   * PATCH /api/library-books/tags/{id}
   * @group library
   * @group library-book-tag
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_book_tag()
  {
    $bookTag = LibraryBookTag::factory()->create();
    $bookTagUpdate = LibraryBookTag::factory()->make()->toArray();
    $res = $this->patchJson('/api/library-books/tags/' . $bookTag->id, $bookTagUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/library-books/tags/{id}
   * @group library
   * @group library-book-tag
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_book_tag()
  {
    $bookTag = LibraryBookTag::factory()->create();
    $bookTagUpdate = LibraryBookTag::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/tags/' . $bookTag->id, $bookTagUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/library-books/tags/{id}
   * @group library
   * @group library-book-tag
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_book_tag()
  {
    Permission::factory()->state(['name' => 'update library book tag'])->create();
    $this->user->givePermissionTo('update library book tag');

    $bookTag = LibraryBookTag::factory()->create();
    $bookTagUpdate = LibraryBookTag::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/tags/' . $bookTag->id, $bookTagUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/library-books/tags/{id}
   * @group library
   * @group library-book-tag
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update library book tag'])->create();
    $this->user->givePermissionTo('update library book tag');
    $bookTag = LibraryBookTag::factory()->create();
    $bookTagUpdate = LibraryBookTag::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/tags/' . $bookTag->id, $bookTagUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/library-books/tags/{id}
   * @group library
   * @group library-book-tag
   * @test
   * @group patch-request
   * @return void
   */
  public function library_book_tag_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update library book tag'])->create();
    $this->user->givePermissionTo('update library book tag');
    $bookTag = LibraryBookTag::factory()->create();
    $bookTagUpdate = LibraryBookTag::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/library-books/tags/' . $bookTag->id, $bookTagUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE/api/library-books/tags/{id}
   * @group library
   * @group library-book-tag
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_book_tag()
  {
    $bookTag = LibraryBookTag::factory()->create();
    $this->deleteJson('/api/library-books/tags/' . $bookTag->id)
      ->assertStatus(401);

  }

  /**
   * DELETE/api/library-books/tags/{id}
   * @group library
   * @group library-book-tag
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_book_tag()
  {
    $bookTag = LibraryBookTag::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-books/tags/' . $bookTag->id)
      ->assertStatus(403);
  }

  /**
   * DELETE/api/library-books/tags/{id}
   * @group library
   * @group library-book-tag
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_book_tag()
  {
    Permission::factory()->state(['name' => 'delete library book tag'])->create();
    $this->user->givePermissionTo('delete library book tag');
    $bookTag = LibraryBookTag::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-books/tags/' . $bookTag->id)
      ->assertStatus(200);
  }

  /**
   * DELETE/api/library-books/tags/{id}
   * @group library
   * @group library-book-tag
   * @test
   * @group delete-request
   * @return void
   */
  public function library_book_tag_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete library book tag'])->create();
    $this->user->givePermissionTo('delete library book tag');
    $bookTag = LibraryBookTag::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/library-books/tags/' . $bookTag->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(LibraryBookTag::find($bookTag->id));
  }

  protected function setUp(): void
  {
    parent::setUp();
    $this->bookTag = LibraryBookTag::factory()->make()->toArray();
  }
}



