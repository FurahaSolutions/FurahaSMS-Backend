<?php


namespace Okotieno\SupportStaff\Tests\Unit;


use App\Models\User;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\PermissionsAndRoles\Models\Role;
use Tests\TestCase;

class SupportStaffTest extends TestCase
{
  /**
   * GET /api/support-staffs/:guardian
   * @test
   * @group support-staffs
   * @get-request
   */
  public function unauthenticated_users_cannot_access_support_staff_details()
  {
    $supportStaff = User::factory()->create();
    $this->getJson('api/support-staffs/' . $supportStaff->id)
      ->assertStatus(401);
  }

  /**
   * GET /api/support-staffs/:guardian
   * @test
   * @group support-staffs
   * @get-request
   */
  public function authenticated_users_can_access_support_staff_details()
  {
    $supportStaff = User::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('api/support-staffs/' . $supportStaff->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'first_name', 'last_name', 'gender_name', 'religion_name']);
  }


//  /**
//   * PATCH /api/support-staffs/:support-staff
//   * @test
//   * @group support-staffs
//   * @group get-request
//   */
//  public function authenticated_users_with_permission_can_update_support_staffs()
//  {
//    Permission::factory()->state(['name' => 'update support staff'])->create();
//    $this->user->givePermissionTo('update support staff');
//    $supportStaff = User::factory()->create();
//    $supportStaffUpdate = User::factory()->make()->toArray();
//    $this->actingAs($this->user, 'api')
//      ->patchJson('api/support-staffs/' . $supportStaff->id, $supportStaffUpdate)
//      ->assertStatus(200)
//      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'first_name', 'last_name']]);
//  }

  /**
   * POST /api/support-staffs/:support-staff
   * @test
   * @group support-staffs
   * @group get-request
   */
  public function authenticated_users_with_permission_can_create_support_staffs()
  {
    Permission::factory()->state(['name' => 'create support staff'])->create();
    $this->user->givePermissionTo('create support staff');
    $supportStaff = User::factory()->make();
    $supportStaff['staff_type'] = Role::factory()->create()->id;
    $this->actingAs($this->user, 'api')
      ->postJson('api/support-staffs', $supportStaff->toArray())
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'first_name', 'last_name']]);
  }

  /**
   * POST /api/support-staffs
   * @test
   * @group support-staffs
   * @group get-request
   */
  public function authenticated_users_without_permission_cannot_create_support_staffs()
  {
    $supportStaff = User::factory()->make();
    $this->actingAs($this->user, 'api')
      ->postJson('api/support-staffs', $supportStaff->toArray())
      ->assertStatus(403);
  }
}
