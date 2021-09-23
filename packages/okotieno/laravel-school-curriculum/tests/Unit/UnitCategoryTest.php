<?php

namespace Okotieno\SchoolCurriculum\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\UnitCategory;
use Okotieno\SchoolStreams\Models\Stream;
use Tests\TestCase;


class UnitCategoryTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $unitCategory;


  protected function setUp(): void
  {
    parent::setUp();
    $this->unitCategory = UnitCategory::factory()->make()->toArray();
  }

  /**
   * GET /api/curriculum/unit-categories
   * @group curriculum
   * @group unit-category
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_unit_categories()
  {
    $this->getJson('/api/curriculum/unit-categories', $this->unitCategory)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/unit-categories
   * @group curriculum
   * @group unit-category
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_categories()
  {
    UnitCategory::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/unit-categories')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/curriculum/unit-categories
   * @group curriculum
   * @group unit-category
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_categories_with_units()
  {
    UnitCategory::factory()->state(['active' => true])->count(2)->create();
    $this->actingAs($this->user, 'api')
      ->getJson("/api/curriculum/unit-categories?only_active=true")
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }
  /**
   * GET /api/curriculum/unit-categories/:id
   * @group curriculum
   * @group unit-category
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_unit_category()
  {
    $unitCategory = UnitCategory::factory()->create();
    $this->getJson('/api/curriculum/unit-categories/' . $unitCategory->id, $this->unitCategory)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/unit-categories/:id
   * @group curriculum
   * @group unit-category
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_category()
  {
    $unitCategory = UnitCategory::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/unit-categories/' . $unitCategory->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }

  /**
   * GET /api/curriculum/unit-categories/:id
   * @group curriculum
   * @group unit-category
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_category_with_units()
  {
    $unitCategory = UnitCategory::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson("/api/curriculum/unit-categories/{$unitCategory->id}?include_units=true")
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name', 'units']);

  }


  /**
   * POST /api/curriculum/unit-categories
   * @group curriculum
   * @group unit-category
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_unit_category()
  {
    $this->postJson('/api/curriculum/unit-categories', $this->unitCategory)
      ->assertStatus(401);

  }

  /**
   * POST /api/curriculum/unit-categories
   * @group curriculum
   * @group unit-category
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_unit_category()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/unit-categories', $this->unitCategory)
      ->assertStatus(403);
  }

  /**
   * POST /api/curriculum/unit-categories
   * @group curriculum
   * @group unit-category
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_unit_category()
  {
    Permission::factory()->state(['name' => 'create unit category'])->create();
    $this->user->givePermissionTo('create unit category');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/curriculum/unit-categories', $this->unitCategory)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * POST /api/curriculum/unit-categories
   * @group curriculum
   * @group unit-category
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->unitCategory['name'] = '';
    Permission::factory()->state(['name' => 'create unit category'])->create();
    $this->user->givePermissionTo('create unit category');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/unit-categories', $this->unitCategory)
      ->assertStatus(422);
  }


  /**
   * POST /api/curriculum/unit-categories
   * @group curriculum
   * @group unit-category
   * @test
   * @group post-request
   * @return void
   */
  public function unit_category_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create unit category'])->create();
    $this->user->givePermissionTo('create unit category');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/unit-categories', $this->unitCategory)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $unitCategory = UnitCategory::where('name', $this->unitCategory['name'])
      ->where('name', $this->unitCategory['name'])->first();
    $this->assertNotNull($unitCategory);
  }


  /**
   * PATCH /api/curriculum/unit-categories/{id}
   * @group curriculum
   * @group unit-category
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_unit_category()
  {
    $unitCategory = UnitCategory::factory()->create();
    $unitCategoryUpdate = UnitCategory::factory()->make()->toArray();
    $res = $this->patchJson('/api/curriculum/unit-categories/' . $unitCategory->id, $unitCategoryUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/curriculum/unit-categories/{id}
   * @group curriculum
   * @group unit-category
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_unit_category()
  {
    $unitCategory = UnitCategory::factory()->create();
    $unitCategoryUpdate = UnitCategory::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/unit-categories/' . $unitCategory->id, $unitCategoryUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/curriculum/unit-categories/{id}
   * @group curriculum
   * @group unit-category
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_unit_category()
  {
    Permission::factory()->state(['name' => 'update unit category'])->create();
    $this->user->givePermissionTo('update unit category');

    $unitCategory = UnitCategory::factory()->create();
    $unitCategoryUpdate = UnitCategory::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/unit-categories/' . $unitCategory->id, $unitCategoryUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/curriculum/unit-categories/{id}
   * @group curriculum
   * @group unit-category
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update unit category'])->create();
    $this->user->givePermissionTo('update unit category');
    $unitCategory = UnitCategory::factory()->create();
    $unitCategoryUpdate = UnitCategory::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/unit-categories/' . $unitCategory->id, $unitCategoryUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/curriculum/unit-categories/{id}
   * @group curriculum
   * @group unit-category
   * @test
   * @group patch-request
   * @return void
   */
  public function unit_category_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update unit category'])->create();
    $this->user->givePermissionTo('update unit category');
    $unitCategory = UnitCategory::factory()->create();
    $unitCategoryUpdate = UnitCategory::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/unit-categories/' . $unitCategory->id, $unitCategoryUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE /api/curriculum/unit-categories/{id}
   * @group curriculum
   * @group unit-category
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_unit_category()
  {
    $unitCategory = UnitCategory::factory()->create();
    $this->deleteJson('/api/curriculum/unit-categories/' . $unitCategory->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/curriculum/unit-categories/{id}
   * @group curriculum
   * @group unit-category
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_unit_category()
  {
    $unitCategory = UnitCategory::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/unit-categories/' . $unitCategory->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/curriculum/unit-categories/{id}
   * @group curriculum
   * @group unit-category
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_unit_category()
  {
    Permission::factory()->state(['name' => 'delete unit category'])->create();
    $this->user->givePermissionTo('delete unit category');
    $unitCategory = UnitCategory::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/unit-categories/' . $unitCategory->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/curriculum/unit-categories/{id}
   * @group curriculum
   * @group unit-category
   * @test
   * @group delete-request
   * @return void
   */
  public function unit_category_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete unit category'])->create();
    $this->user->givePermissionTo('delete unit category');
    $unitCategory = UnitCategory::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/unit-categories/' . $unitCategory->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(UnitCategory::find($unitCategory->id));
  }
}



