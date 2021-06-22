<?php

namespace Okotieno\SchoolInfrastructure\Tests\Unit;

use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolInfrastructure\Models\Room;
use Tests\TestCase;

class RoomsTest extends TestCase
{
  /**
   * GET /api/school-rooms
   * @group school-rooms
   * @test
   */

  public function unauthenticated_users_cannot_retrieve_rooms()
  {
    $this->getJson('api/school-rooms')->assertUnauthorized();
  }

  /**
   * GET /api/school-rooms
   * @group school-rooms
   * @test
   */

  public function authenticated_users_can_retrieve_rooms()
  {
    $this->actingAs($this->user, 'api')->getJson('api/school-rooms')->assertOk();
  }

  /**
   * GET /api/school-rooms/:room
   * @test
   * @group school-rooms
   */

  public function unauthenticated_users_cannot_retrieve_room()
  {
    $room = Room::factory()->create();
    $this->getJson("api/school-rooms/{$room}")->assertUnauthorized();
  }

  /**
   * GET /api/school-rooms/:room
   * @group school-rooms
   * @test
   */

  public function authenticated_users_can_retrieve_room()
  {
    $room = Room::factory()->create();
    $this->actingAs($this->user, 'api')->getJson("api/school-rooms/{$room}")->assertOk();
  }

  /**
   * POST /api/school-rooms
   * @group school-rooms
   * @test
   */

  public function unauthenticated_users_cannot_create_room()
  {
    $this->postJson('api/school-rooms')->assertUnauthorized();
  }

  /**
   * POST /api/school-rooms
   * @group school-rooms
   * @test
   */

  public function user_without_permission_cannot_create_room()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('api/school-rooms')->assertForbidden();
  }

  /**
   * POST /api/school-rooms
   * @group school-rooms
   * @test
   */

  public function error_422_if_name_not_provided()
  {
    Permission::factory()->state(['name' => 'create room'])->create();
    $this->user->givePermissionTo('create room');
    $this->actingAs($this->user, 'api')
      ->postJson('api/school-rooms')->assertStatus(422);
  }

  /**
   * POST /api/school-rooms
   * @group school-rooms
   * @test
   */

  public function user_with_permission_can_create_room()
  {
    Permission::factory()->state(['name' => 'create room'])->create();
    $this->user->givePermissionTo('create room');
    $room = Room::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->postJson('api/school-rooms', $room)->assertOk();
  }

  /**
   * DELETE /api/school-rooms/:room
   * @test
   * @group school-rooms
   */

  public function unauthenticated_users_cannot_delete_room()
  {
    $room = Room::factory()->create();
    $this->deleteJson("api/school-rooms/{$room}")->assertUnauthorized();
  }

  /**
   * DELETE /api/school-rooms/:room
   * @test
   * @group school-rooms
   */

  public function user_without_permission_cannot_delete_room()
  {
    $room = Room::factory()->create();
    $this->actingAs($this->user, 'api')->deleteJson("api/school-rooms/{$room}")->assertForbidden();
  }

  /**
   * DELETE /api/school-rooms/:room
   * @test
   * @group school-rooms
   */

  public function user_with_permission_can_delete_room()
  {
    Permission::factory()->state(['name' => 'delete room'])->create();
    $this->user->givePermissionTo('delete room');
    $room = Room::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson("api/school-rooms/{$room}")
      ->assertOk()
      ->assertJsonStructure(['saved', 'message']);
  }
}
