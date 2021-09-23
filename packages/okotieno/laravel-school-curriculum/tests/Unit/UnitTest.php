<?php

namespace Okotieno\SchoolCurriculum\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\Unit;
use Tests\TestCase;


class UnitTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $unit;


  protected function setUp(): void
  {
    parent::setUp();
    $this->unit = Unit::factory()->make()->toArray();
  }

  /**
   * GET /api/curriculum/units
   * @group curriculum
   * @group unit
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_units()
  {
    $this->getJson('/api/curriculum/units', $this->unit)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/units
   * @group curriculum
   * @group unit
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_units()
  {
    Unit::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/units')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/curriculum/units
   * @group curriculum
   * @group unit
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_units_with_unit_levels()
  {
    Unit::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')
      ->getJson('/api/curriculum/units?include_unit_levels=true')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name', 'unit_levels']]);

  }

  /**
   * GET /api/curriculum/units/:id
   * @group curriculum
   * @group unit
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_unit()
  {
    $unit = Unit::factory()->create();
    $this->getJson('/api/curriculum/units/' . $unit->id, $this->unit)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/units/:id
   * @group curriculum
   * @group unit
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit()
  {
    $unit = Unit::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/units/' . $unit->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }

  /**
   * GET /api/curriculum/units/:id
   * @group curriculum
   * @group unit
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_unit_with_unit_levels()
  {
    $unit = Unit::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson("/api/curriculum/units/{$unit->id}?include_unit_levels=true")
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name', 'unit_levels']);

  }


  /**
   * POST /api/curriculum/units
   * @group curriculum
   * @group unit
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_unit()
  {
    $this->postJson('/api/curriculum/units', $this->unit)
      ->assertStatus(401);

  }

  /**
   * POST /api/curriculum/units
   * @group curriculum
   * @group unit
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_unit()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/units', $this->unit)
      ->assertStatus(403);
  }

  /**
   * POST /api/curriculum/units
   * @group curriculum
   * @group unit
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_unit()
  {
    Permission::factory()->state(['name' => 'create unit'])->create();
    $this->user->givePermissionTo('create unit');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/curriculum/units', $this->unit)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * POST /api/curriculum/units
   * @group curriculum
   * @group unit
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->unit['name'] = '';
    Permission::factory()->state(['name' => 'create unit'])->create();
    $this->user->givePermissionTo('create unit');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/units', $this->unit)
      ->assertStatus(422);
  }


  /**
   * POST /api/curriculum/units
   * @group curriculum
   * @group unit
   * @test
   * @group post-request
   * @return void
   */
  public function unit_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create unit'])->create();
    $this->user->givePermissionTo('create unit');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/units', $this->unit)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $unit = Unit::where('name', $this->unit['name'])
      ->where('name', $this->unit['name'])->first();
    $this->assertNotNull($unit);
  }


  /**
   * PATCH /api/curriculum/units/{id}
   * @group curriculum
   * @group unit
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_unit()
  {
    $unit = Unit::factory()->create();
    $unitUpdate = Unit::factory()->make()->toArray();
    $res = $this->patchJson('/api/curriculum/units/' . $unit->id, $unitUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/curriculum/units/{id}
   * @group curriculum
   * @group unit-1
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_unit()
  {
    $unit = Unit::factory()->create();
    $unitUpdate = Unit::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/units/' . $unit->id, $unitUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/curriculum/units/{id}
   * @group curriculum
   * @group unit
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_unit()
  {
    Permission::factory()->state(['name' => 'update unit'])->create();
    $this->user->givePermissionTo('update unit');

    $unit = Unit::factory()->create();
    $unitUpdate = Unit::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/units/' . $unit->id, $unitUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/curriculum/units/{id}
   * @group curriculum
   * @group unit
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update unit'])->create();
    $this->user->givePermissionTo('update unit');
    $unit = Unit::factory()->create();
    $unitUpdate = Unit::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/units/' . $unit->id, $unitUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/curriculum/units/{id}
   * @group curriculum
   * @group unit
   * @test
   * @group patch-request
   * @return void
   */
  public function unit_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update unit'])->create();
    $this->user->givePermissionTo('update unit');
    $unit = Unit::factory()->create();
    $unitUpdate = Unit::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/units/' . $unit->id, $unitUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE /api/curriculum/units/{id}
   * @group curriculum
   * @group unit
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_unit()
  {
    $unit = Unit::factory()->create();
    $this->deleteJson('/api/curriculum/units/' . $unit->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/curriculum/units/{id}
   * @group curriculum
   * @group unit
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_unit()
  {
    $unit = Unit::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/units/' . $unit->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/curriculum/units/{id}
   * @group curriculum
   * @group unit
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_unit()
  {
    Permission::factory()->state(['name' => 'delete unit'])->create();
    $this->user->givePermissionTo('delete unit');
    $unit = Unit::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/units/' . $unit->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/curriculum/units/{id}
   * @group curriculum
   * @group unit
   * @test
   * @group delete-request
   * @return void
   */
  public function unit_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete unit'])->create();
    $this->user->givePermissionTo('delete unit');
    $unit = Unit::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/units/' . $unit->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(Unit::find($unit->id));
  }
}



