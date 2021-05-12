<?php

namespace Tests\Unit;

use App\Models\User;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;

class UserTest extends TestCase
{

  protected function setUp(): void
  {
    parent::setUp();

  }

  /**
   * GET api/user
   * @group users
   * @test
   */
  public function unauthenticated_users_cannot_get_users()
  {
    $name = $this->faker->firstName;
    User::factory()->state(['first_name' => $name])->create();
    $this->getJson('api/users?name=' . $name)
      ->assertStatus(401);
  }

  /**
   * GET api/user
   * @group users
   * @test
   */
  public function authenticated_users_can_get_users()
  {
    $name = $this->faker->firstName;
    User::factory()->state(['first_name' => $name])->create();
    $this->actingAs($this->user, 'api')
      ->getJson('api/users?name=' . $name)
      ->assertStatus(200);
  }

  /**
   * POST api/user
   * @group users
   * @test
   */
  public function unauthenticated_users_cannot_reset_user_password()
  {
    $user = User::factory()->create();
    $this->postJson('api/users/' . $user->id . '/password-reset', ['reset_password' => $this->faker->password])
      ->assertStatus(401);
  }

  /**
   * POST api/user
   * @group users
   * @test
   */
  public function authenticated_users_without_permission_cannot_reset_user_password()
  {
    $user = User::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/users/' . $user->id . '/password-reset', ['reset_password' => $this->faker->password])
      ->assertStatus(403);
  }

  /**
   * POST api/user
   * @group users
   * @test
   */
  public function authenticated_users_with_permission_can_reset_user_password()
  {
    $user = User::factory()->create();
    Permission::factory()->state(['name' => 'reset user password'])->create();
    $this->user->givePermissionTo('reset user password');
    $this->actingAs($this->user, 'api')
      ->postJson('api/users/' . $user->id . '/password-reset', ['reset_password' => $this->faker->password])
      ->assertStatus(200);
  }

}


