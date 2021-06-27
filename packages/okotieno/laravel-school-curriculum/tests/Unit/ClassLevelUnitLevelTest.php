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
   * @group class-level-unit-level
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_unit_allocations()
  {
    $this->getJson('/api/curriculum/class-levels/unit-levels')
      ->assertUnauthorized();

  }

  /**
   * GET /api/curriculum/class-levels/unit-levels
   * @group curriculum
   * @group class-level-unit-level
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_allocations()
  {
    $this->actingAs($this->user, 'api')
      ->getJson('/api/curriculum/class-levels/unit-levels')
      ->assertOk();

  }

  /**
   * POST /api/curriculum/class-levels/unit-levels
   * @group curriculum
   * @group class-level-unit-level
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_allocate_unit_levels()
  {
    $this->postJson('/api/curriculum/class-levels/unit-levels')
      ->assertUnauthorized();

  }

  /**
   * POST /api/curriculum/class-levels/unit-levels
   * @group curriculum
   * @group class-level-unit-level
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_allocate_unit_levels()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('/api/curriculum/class-levels/unit-levels')
      ->assertForbidden();

  }

  /**
   * POST /api/curriculum/class-levels/unit-levels
   * @group curriculum
   * @group class-level-unit-level
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_allocate_unit_levels()
  {
    Permission::factory()->state(['name' => 'allocate unit levels to class levels'])->create();
    $this->user->givePermissionTo('allocate unit levels to class levels');
    $data = array_map(function ($classLevel){
      return array_merge($classLevel, [
        'unitLevels' => UnitLevel::factory()->count(3)->create()->pluck('id')
      ]);
    }, ClassLevel::factory()->count(2)->create()->toArray());
    $this->actingAs($this->user, 'api')
      ->postJson('/api/curriculum/class-levels/unit-levels', $data)
      ->assertCreated();

  }

}
