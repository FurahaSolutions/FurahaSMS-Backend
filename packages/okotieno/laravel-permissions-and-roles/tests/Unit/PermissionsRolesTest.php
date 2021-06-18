<?php


namespace Okotieno\PermissionsAndRoles\Tests\Unit;


use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\PermissionsAndRoles\Models\Role;
use Tests\TestCase;

class PermissionsRolesTest extends TestCase
{
  public $role;

  protected function setUp(): void
  {
    parent::setUp();

  }

  /**
   * GET /api/permissions-and-roles/roles
   * @group permissions-and-roles
   * @test
   */

  public function unauthenticated_users_cannot_retrieve_permission_and_roles()
  {
    $this->getJson('/api/permissions-and-roles/roles')
      ->assertUnauthorized();
  }

  /**
   * GET /api/permissions-and-roles/roles
   * @group permissions-and-roles
   * @test
   */

  public function authenticated_users_can_retrieve_permission_and_roles()
  {
    $roles = Role::factory()->count(3)->create();
    $permissions = Permission::factory()->count(3)->create();
    $roles[0]->permissions()->save($permissions[1]);
    $roles[0]->permissions()->save($permissions[2]);
    $roles[1]->permissions()->save($permissions[2]);
    $this->actingAs($this->user, 'api')
      ->getJson('/api/permissions-and-roles/roles')
      ->assertOk();
  }

  /**
   * GET /api/permissions-and-roles/roles
   * @group permissions-and-roles
   * @test
   */

  public function authenticated_users_can_retrieve_staff_types()
  {
    Role::factory()->count(2)->staff()->create();
    Role::factory()->count(2)->nonStaff()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('/api/permissions-and-roles/roles?staff=true')
      ->assertOk();
  }

  /**
   * GET /api/permissions-and-roles/roles/:role
   * @group permissions-and-roles
   * @test
   */

  public function authenticated_users_can_retrieve_role_permissions()
  {
    $role = Role::factory()->staff()->create();
    $permissions = Permission::factory()->count(3)->create();
    $role->permissions()->save($permissions[1]);
    $role->permissions()->save($permissions[2]);
    $this->actingAs($this->user, 'api')
      ->getJson('/api/permissions-and-roles/roles/'.$role->id)
      ->assertOk();
  }  /**
   * GET /api/permissions-and-roles/roles/:role?allPermissions=true
   * @group permissions-and-roles
   * @test
   */

  public function authenticated_users_can_retrieve_role_all_permissions()
  {
    $role = Role::factory()->staff()->create();
    $permissions = Permission::factory()->count(3)->create();
    $role->permissions()->save($permissions[1]);
    $role->permissions()->save($permissions[2]);
    $this->actingAs($this->user, 'api')
      ->getJson('/api/permissions-and-roles/roles/'.$role->id.'?allPermissions=true')
      ->assertOk();
  }


}

