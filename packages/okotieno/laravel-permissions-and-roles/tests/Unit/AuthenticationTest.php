<?php


namespace Okotieno\PermissionsAndRoles\Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\PermissionsAndRoles\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;


  protected function setUp(): void
  {
    parent::setUp();
  }

  /**
   * Test get '/api/users/auth'
   *
   * @return void
   * @group get-request
   * @group permission-roles
   * @group authentication
   *
   */
  public function testUserRolesAndPermissionsAreIncludedInApi()
  {
    $role = Role::create(['name' => 'some role']);
    Role::create(['name' => 'another role']);
    $permission = Permission::create(['name' => 'some permission']);
    Permission::create(['name' => 'another permission']);

    $role->permissions()->save($permission);
    $this->user->assignRole('some role');
    $this->actingAs($this->user, 'api')
      ->get('/api/users/auth')
      ->assertJsonFragment(['some role'])
      ->assertJsonFragment(['some permission'])
      ->assertJsonMissing(['another permission']);
  }
}

