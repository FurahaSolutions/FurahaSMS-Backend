<?php

namespace Okotieno\SchoolCurriculum\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\AcademicYearUnitAllocation;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Okotieno\SchoolStreams\Models\Stream;
use Tests\TestCase;


class UnitLevelTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $unitLevel;


  protected function setUp(): void
  {
    parent::setUp();
    $this->unitLevel = UnitLevel::factory()->make()->toArray();
  }

  /**
   * GET /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_unit_levels()
  {
    $this->getJson('/api/curriculum/unit-levels', $this->unitLevel)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_levels()
  {
    UnitLevel::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/unit-levels', $this->unitLevel)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }


  /**
   * GET /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_levels_by_academic_year_id_and_class_level_id()
  {
    $classLevels = ClassLevel::factory()->count(3)->create();
    $academicYears = AcademicYear::factory()->count(3)->create();

    AcademicYearUnitAllocation::factory()->state([
      'academic_year_id'=> $academicYears[1]->id,
      'class_level_id'=> $classLevels[0]->id
    ])->count(2)->create();

    $this->actingAs($this->user, 'api')
      ->getJson("/api/curriculum/unit-levels?academic_year_id={$academicYears[1]->id}&class_level_id={$classLevels[0]->id}")
      ->assertStatus(200)
      ->assertJsonStructure([['unit_level_name']]);

  }

  /**
   * GET /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_levels_by_academic_year_id()
  {
    $classLevels = ClassLevel::factory()->count(3)->create();
    $academicYears = AcademicYear::factory()->count(3)->create();

    AcademicYearUnitAllocation::factory()->state([
      'academic_year_id'=> $academicYears[1]->id,
      'class_level_id'=> $classLevels[0]->id
    ])->count(2)->create();

    $this->actingAs($this->user, 'api')
      ->getJson("/api/curriculum/unit-levels?academic_year_id={$academicYears[1]->id}")
      ->assertStatus(200)
      ->assertJsonStructure([['unit_level_name']]);

  }

  /**
   * GET /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_levels_by_class_level_id()
  {
    $classLevels = ClassLevel::factory()->count(3)->create();
    $academicYears = AcademicYear::factory()->count(3)->create();

    AcademicYearUnitAllocation::factory()->state([
      'academic_year_id'=> $academicYears[1]->id,
      'class_level_id'=> $classLevels[0]->id
    ])->count(2)->create();

    $this->actingAs($this->user, 'api')
      ->getJson("/api/curriculum/unit-levels?class_level_id={$classLevels[0]->id}")
      ->assertStatus(200)
      ->assertJsonStructure([['unit_level_name']]);

  }
  /**
   * GET /api/curriculum/unit-levels/:id
   * @group curriculum
   * @group unit-level
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_unit_level()
  {
    $unitLevel = UnitLevel::factory()->create();
    $this->getJson('/api/curriculum/unit-levels/' . $unitLevel->id, $this->unitLevel)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/unit-levels/:id
   * @group curriculum
   * @group unit-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_level()
  {
    $unitLevel = UnitLevel::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/unit-levels/' . $unitLevel->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }


  /**
   * POST /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_unit_level()
  {
    $this->postJson('/api/curriculum/unit-levels', $this->unitLevel)
      ->assertStatus(401);

  }

  /**
   * POST /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_unit_level()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/unit-levels', $this->unitLevel)
      ->assertStatus(403);
  }

  /**
   * POST /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_unit_level()
  {
    Permission::factory()->state(['name' => 'create unit level'])->create();
    $this->user->givePermissionTo('create unit level');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/curriculum/unit-levels', $this->unitLevel)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * POST /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->unitLevel['name'] = '';
    Permission::factory()->state(['name' => 'create unit level'])->create();
    $this->user->givePermissionTo('create unit level');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/unit-levels', $this->unitLevel)
      ->assertStatus(422);
  }


  /**
   * POST /api/curriculum/unit-levels
   * @group curriculum
   * @group unit-level
   * @test
   * @group post-request
   * @return void
   */
  public function unit_level_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create unit level'])->create();
    $this->user->givePermissionTo('create unit level');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/unit-levels', $this->unitLevel)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $unitLevel = UnitLevel::where('name', $this->unitLevel['name'])
      ->where('name', $this->unitLevel['name'])->first();
    $this->assertNotNull($unitLevel);
  }


  /**
   * PATCH /api/curriculum/unit-levels/{id}
   * @group curriculum
   * @group unit-level
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_unit_level()
  {
    $unitLevel = UnitLevel::factory()->create();
    $unitLevelUpdate = UnitLevel::factory()->make()->toArray();
    $res = $this->patchJson('/api/curriculum/unit-levels/' . $unitLevel->id, $unitLevelUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/curriculum/unit-levels/{id}
   * @group curriculum
   * @group unit-level
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_unit_level()
  {
    $unitLevel = UnitLevel::factory()->create();
    $unitLevelUpdate = UnitLevel::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/unit-levels/' . $unitLevel->id, $unitLevelUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/curriculum/unit-levels/{id}
   * @group curriculum
   * @group unit-level
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_unit_level()
  {
    Permission::factory()->state(['name' => 'update unit level'])->create();
    $this->user->givePermissionTo('update unit level');

    $unitLevel = UnitLevel::factory()->create();
    $unitLevelUpdate = UnitLevel::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/unit-levels/' . $unitLevel->id, $unitLevelUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/curriculum/unit-levels/{id}
   * @group curriculum
   * @group unit-level
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update unit level'])->create();
    $this->user->givePermissionTo('update unit level');
    $unitLevel = UnitLevel::factory()->create();
    $unitLevelUpdate = UnitLevel::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/unit-levels/' . $unitLevel->id, $unitLevelUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/curriculum/unit-levels/{id}
   * @group curriculum
   * @group unit-level
   * @test
   * @group patch-request
   * @return void
   */
  public function unit_level_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update unit level'])->create();
    $this->user->givePermissionTo('update unit level');
    $unitLevel = UnitLevel::factory()->create();
    $unitLevelUpdate = UnitLevel::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/unit-levels/' . $unitLevel->id, $unitLevelUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE /api/curriculum/unit-levels/{id}
   * @group curriculum
   * @group unit-level
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_unit_level()
  {
    $unitLevel = UnitLevel::factory()->create();
    $this->deleteJson('/api/curriculum/unit-levels/' . $unitLevel->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/curriculum/unit-levels/{id}
   * @group curriculum
   * @group unit-level
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_unit_level()
  {
    $unitLevel = UnitLevel::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/unit-levels/' . $unitLevel->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/curriculum/unit-levels/{id}
   * @group curriculum
   * @group unit-level
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_unit_level()
  {
    Permission::factory()->state(['name' => 'delete unit level'])->create();
    $this->user->givePermissionTo('delete unit level');
    $unitLevel = UnitLevel::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/unit-levels/' . $unitLevel->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/curriculum/unit-levels/{id}
   * @group curriculum
   * @group unit-level
   * @test
   * @group delete-request
   * @return void
   */
  public function unit_level_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete unit level'])->create();
    $this->user->givePermissionTo('delete unit level');
    $unitLevel = UnitLevel::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/unit-levels/' . $unitLevel->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(UnitLevel::find($unitLevel->id));
  }
}



