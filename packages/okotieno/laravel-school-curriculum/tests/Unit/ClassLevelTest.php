<?php

namespace Okotieno\SchoolCurriculum\Tests\Unit;

use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Tests\TestCase;


class ClassLevelTest extends TestCase
{
  private $classLevel;

  protected function setUp(): void
  {
    parent::setUp();
    $this->classLevel = ClassLevel::factory()->make()->toArray();
  }

  /**
   * GET /api/curriculum/class-levels
   * @group curriculum
   * @group class-level
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_class_levels()
  {
    $this->getJson('/api/curriculum/class-levels', $this->classLevel)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/class-levels
   * @group curriculum
   * @group class-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_class_levels()
  {
    ClassLevel::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/class-levels')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/curriculum/class-levels
   * @group curriculum
   * @group class-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_class_levels_with_levels()
  {
    $classLevels = ClassLevel::factory()->count(3)->create();
    foreach ($classLevels as $classLevel) {
      $classLevel->unitLevels()->save(UnitLevel::factory()->create(), [
        'academic_year_id' => AcademicYear::factory()->create()->id
      ]);
    }

    $this->actingAs($this->user, 'api')
      ->getJson('/api/curriculum/class-levels?include_levels=true')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }


  /**
   * GET /api/curriculum/class-levels
   * @group curriculum
   * @group class-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_class_levels_with_levels_by_academic_year()
  {
    $academicYearId = AcademicYear::factory()->create()->id;
    $classLevels = ClassLevel::factory()->count(3)->create();
    foreach ($classLevels as $classLevel) {
      $classLevel->unitLevels()->save(UnitLevel::factory()->create(), [
        'academic_year_id' => $academicYearId
      ]);
      $classLevel->unitLevels()->save(UnitLevel::factory()->create(), [
        'academic_year_id' => AcademicYear::factory()->create()->id
      ]);
    }

    $this->actingAs($this->user, 'api')
      ->getJson("/api/curriculum/class-levels?include_levels=true&academic_year_id={$academicYearId}")
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/curriculum/class-levels/:id
   * @group curriculum
   * @group class-level
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_class_level()
  {
    $classLevel = ClassLevel::factory()->create();
    $this->getJson('/api/curriculum/class-levels/' . $classLevel->id, $this->classLevel)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/class-levels/:id
   * @group curriculum
   * @group class-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_class_level()
  {
    $classLevel = ClassLevel::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/class-levels/' . $classLevel->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }


  /**
   * POST /api/curriculum/class-levels
   * @group curriculum
   * @group class-level
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_class_level()
  {
    $this->postJson('/api/curriculum/class-levels', $this->classLevel)
      ->assertStatus(401);

  }

  /**
   * POST /api/curriculum/class-levels
   * @group curriculum
   * @group class-level
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_class_level()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/class-levels', $this->classLevel)
      ->assertStatus(403);
  }

  /**
   * POST /api/curriculum/class-levels
   * @group curriculum
   * @group class-level
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_class_level()
  {
    Permission::factory()->state(['name' => 'create class level'])->create();
    $this->user->givePermissionTo('create class level');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/curriculum/class-levels', $this->classLevel)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * POST /api/curriculum/class-levels
   * @group curriculum
   * @group class-level
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->classLevel['name'] = '';
    Permission::factory()->state(['name' => 'create class level'])->create();
    $this->user->givePermissionTo('create class level');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/class-levels', $this->classLevel)
      ->assertStatus(422);
  }


  /**
   * POST /api/curriculum/class-levels
   * @group curriculum
   * @group class-level
   * @test
   * @group post-request
   * @return void
   */
  public function class_level_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create class level'])->create();
    $this->user->givePermissionTo('create class level');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/class-levels', $this->classLevel)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $classLevel = ClassLevel::where('name', $this->classLevel['name'])
      ->where('name', $this->classLevel['name'])->first();
    $this->assertNotNull($classLevel);
  }


  /**
   * PATCH /api/curriculum/class-levels/{id}
   * @group curriculum
   * @group class-level
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_class_level()
  {
    $classLevel = ClassLevel::factory()->create();
    $classLevelUpdate = ClassLevel::factory()->make()->toArray();
    $res = $this->patchJson('/api/curriculum/class-levels/' . $classLevel->id, $classLevelUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/curriculum/class-levels/{id}
   * @group curriculum
   * @group class-level
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_class_level()
  {
    $classLevel = ClassLevel::factory()->create();
    $classLevelUpdate = ClassLevel::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/class-levels/' . $classLevel->id, $classLevelUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/curriculum/class-levels/{id}
   * @group curriculum
   * @group class-level
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_class_level()
  {
    Permission::factory()->state(['name' => 'update class level'])->create();
    $this->user->givePermissionTo('update class level');

    $classLevel = ClassLevel::factory()->create();
    $classLevelUpdate = ClassLevel::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/class-levels/' . $classLevel->id, $classLevelUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/curriculum/class-levels/{id}
   * @group curriculum
   * @group class-level
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update class level'])->create();
    $this->user->givePermissionTo('update class level');
    $classLevel = ClassLevel::factory()->create();
    $classLevelUpdate = ClassLevel::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/class-levels/' . $classLevel->id, $classLevelUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/curriculum/class-levels/{id}
   * @group curriculum
   * @group class-level
   * @test
   * @group patch-request
   * @return void
   */
  public function class_level_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update class level'])->create();
    $this->user->givePermissionTo('update class level');
    $classLevel = ClassLevel::factory()->create();
    $classLevelUpdate = ClassLevel::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/class-levels/' . $classLevel->id, $classLevelUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE /api/curriculum/class-levels/{id}
   * @group curriculum
   * @group class-level
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_class_level()
  {
    $classLevel = ClassLevel::factory()->create();
    $this->deleteJson('/api/curriculum/class-levels/' . $classLevel->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/curriculum/class-levels/{id}
   * @group curriculum
   * @group class-level
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_class_level()
  {
    $classLevel = ClassLevel::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/class-levels/' . $classLevel->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/curriculum/class-levels/{id}
   * @group curriculum
   * @group class-level
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_class_level()
  {
    Permission::factory()->state(['name' => 'delete class level'])->create();
    $this->user->givePermissionTo('delete class level');
    $classLevel = ClassLevel::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/class-levels/' . $classLevel->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/curriculum/class-levels/{id}
   * @group curriculum
   * @group class-level
   * @test
   * @group delete-request
   * @return void
   */
  public function class_level_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete class level'])->create();
    $this->user->givePermissionTo('delete class level');
    $classLevel = ClassLevel::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/class-levels/' . $classLevel->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(ClassLevel::find($classLevel->id));
  }
}



