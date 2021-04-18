<?php

namespace Okotieno\SchoolCurriculum\Tests\Unit;

use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\ClassLevelCategory;
use Tests\TestCase;


class ClassLevelCategoryTest extends TestCase
{
  private $classLevelCategory;

  protected function setUp(): void
  {
    parent::setUp();
    $this->classLevelCategory = ClassLevelCategory::factory()->make()->toArray();
  }

  /**
   * GET /api/curriculum/class-level-categories
   * @group curriculum
   * @group class-level-category
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_class_level_categorys()
  {
    $this->getJson('/api/curriculum/class-level-categories', $this->classLevelCategory)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/class-level-categories
   * @group curriculum
   * @group class-level-category
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_class_level_categories()
  {
    ClassLevelCategory::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/class-level-categories', $this->classLevelCategory)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/curriculum/class-level-categories/:id
   * @group curriculum
   * @group class-level-category
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_class_level_category()
  {
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $this->getJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id, $this->classLevelCategory)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/class-level-categories/:id
   * @group curriculum
   * @group class-level-category
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_class_level_category()
  {
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }


  /**
   * POST /api/curriculum/class-level-categories
   * @group curriculum
   * @group class-level-category
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_class_level_category()
  {
    $this->postJson('/api/curriculum/class-level-categories', $this->classLevelCategory)
      ->assertStatus(401);

  }

  /**
   * POST /api/curriculum/class-level-categories
   * @group curriculum
   * @group class-level-category
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_class_level_category()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/class-level-categories', $this->classLevelCategory)
      ->assertStatus(403);
  }

  /**
   * POST /api/curriculum/class-level-categories
   * @group curriculum
   * @group class-level-category
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_class_level_category()
  {
    Permission::factory()->state(['name' => 'create class level category'])->create();
    $this->user->givePermissionTo('create class level category');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/curriculum/class-level-categories', $this->classLevelCategory)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * POST /api/curriculum/class-level-categories
   * @group curriculum
   * @group class-level-category
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->classLevelCategory['name'] = '';
    Permission::factory()->state(['name' => 'create class level category'])->create();
    $this->user->givePermissionTo('create class level category');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/class-level-categories', $this->classLevelCategory)
      ->assertStatus(422);
  }


  /**
   * POST /api/curriculum/class-level-categories
   * @group curriculum
   * @group class-level-category
   * @test
   * @group post-request
   * @return void
   */
  public function class_level_category_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create class level category'])->create();
    $this->user->givePermissionTo('create class level category');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/class-level-categories', $this->classLevelCategory)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $classLevelCategory = ClassLevelCategory::where('name', $this->classLevelCategory['name'])
      ->where('name', $this->classLevelCategory['name'])->first();
    $this->assertNotNull($classLevelCategory);
  }


  /**
   * PATCH /api/curriculum/class-level-categories/{id}
   * @group curriculum
   * @group class-level-category
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_class_level_category()
  {
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $classLevelCategoryUpdate = ClassLevelCategory::factory()->make()->toArray();
    $res = $this->patchJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id, $classLevelCategoryUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/curriculum/class-level-categories/{id}
   * @group curriculum
   * @group class-level-category
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_class_level_category()
  {
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $classLevelCategoryUpdate = ClassLevelCategory::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id, $classLevelCategoryUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/curriculum/class-level-categories/{id}
   * @group curriculum
   * @group class-level-category
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_class_level_category()
  {
    Permission::factory()->state(['name' => 'update class level category'])->create();
    $this->user->givePermissionTo('update class level category');

    $classLevelCategory = ClassLevelCategory::factory()->create();
    $classLevelCategoryUpdate = ClassLevelCategory::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id, $classLevelCategoryUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/curriculum/class-level-categories/{id}
   * @group curriculum
   * @group class-level-category
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update class level category'])->create();
    $this->user->givePermissionTo('update class level category');
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $classLevelCategoryUpdate = ClassLevelCategory::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id, $classLevelCategoryUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/curriculum/class-level-categories/{id}
   * @group curriculum
   * @group class-level-category
   * @test
   * @group patch-request
   * @return void
   */
  public function class_level_category_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update class level category'])->create();
    $this->user->givePermissionTo('update class level category');
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $classLevelCategoryUpdate = ClassLevelCategory::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id, $classLevelCategoryUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE /api/curriculum/class-level-categories/{id}
   * @group curriculum
   * @group class-level-category
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_class_level_category()
  {
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $this->deleteJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/curriculum/class-level-categories/{id}
   * @group curriculum
   * @group class-level-category
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_class_level_category()
  {
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/curriculum/class-level-categories/{id}
   * @group curriculum
   * @group class-level-category
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_class_level_category()
  {
    Permission::factory()->state(['name' => 'delete class level category'])->create();
    $this->user->givePermissionTo('delete class level category');
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/curriculum/class-level-categories/{id}
   * @group curriculum
   * @group class-level-category
   * @test
   * @group delete-request
   * @return void
   */
  public function class_level_category_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete class level category'])->create();
    $this->user->givePermissionTo('delete class level category');
    $classLevelCategory = ClassLevelCategory::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/class-level-categories/' . $classLevelCategory->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(ClassLevelCategory::find($classLevelCategory->id));
  }
}



