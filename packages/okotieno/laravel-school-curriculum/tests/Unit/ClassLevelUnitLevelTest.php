<?php

namespace Okotieno\SchoolCurriculum\Tests\Unit;

use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Tests\TestCase;


class ClassLevelUnitLevelTest extends TestCase
{

  /**
   * GET /api/curriculum/class-levels/unit-levels
   * @group curriculum
   * @group class-level-unit-levels
   * @group get-request
   * @test
   * @return void
   */

  public function unauthenticated_users_cannot_retrieve_class_level_unit_levels()
  {
    $this->getJson('api/curriculum/class-levels/unit-levels')
      ->assertUnauthorized();
  }
  /**
   * GET /api/curriculum/class-levels/unit-levels
   * @group curriculum
   * @group class-level-unit-levels
   * @group get-request
   * @test
   * @return void
   */

  public function authenticated_users_can_retrieve_class_level_unit_levels()
  {
    ClassLevel::factory()->create();
    $this->actingAs($this->user)->getJson('api/curriculum/class-levels/unit-levels')
      ->assertOk()
      ->assertJsonStructure([['id', 'taught_units']]);
  }

  /**
   * POST /api/curriculum/class-levels/unit-levels
   * @group curriculum
   * @group class-level-unit-levels
   * @group post-request
   * @test
   * @return void
   */

  public function unauthenticated_users_cannot_create_class_level_unit_levels()
  {
    $this->postJson('api/curriculum/class-levels/unit-levels', ['allocations' => ''])
      ->assertUnauthorized();
  }

  /**
   * POST /api/curriculum/class-levels/unit-levels
   * @group curriculum
   * @group class-level-unit-levels
   * @group post-request
   * @test
   * @return void
   */

  public function authenticated_users_without_permission_cannot_create_class_level_unit_levels()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('api/curriculum/class-levels/unit-levels', ['allocations' => []])
      ->assertForbidden();
  }

  /**
   * POST /api/curriculum/class-levels/unit-levels
   * @group curriculum
   * @group class-level-unit-levels
   * @group post-request
   * @test
   * @return void
   */

  public function authenticated_users_with_permission_can_create_class_level_unit_levels()
  {
    Permission::factory()->state(['name' => 'allocate unit levels to class levels'])->create();
    $this->user->givePermissionTo('allocate unit levels to class levels');
    $this->actingAs($this->user, 'api')
      ->postJson('api/curriculum/class-levels/unit-levels', ['allocations' => [
        [
          'id' => ClassLevel::factory()->create()->id,
          'unitLevels' => UnitLevel::factory()->count(3)->create()->pluck('id')
        ]
      ]])
      ->assertOk();
  }
}
