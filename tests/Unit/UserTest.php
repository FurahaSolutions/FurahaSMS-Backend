<?php

namespace Tests\Unit;

use App\Models\User;
use Carbon\Carbon;
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

  /**
   * PATCH api/user/:user
   * @group users
   * @test
   */
  public function unauthenticated_users_cannot_update_user_profile()
  {
    $user = User::factory()->create();
    $this->patchJson('api/users/' . $user->id, ['first_name' => $this->faker->firstName])
      ->assertStatus(401);
  }
  /**
   * PATCH api/user/:user
   * @group users
   * @test
   */
  public function authenticated_users_without_permission_cannot_update_user_profile()
  {
    $user = User::factory()->create();
    $this->actingAs($this->user, 'api')
      ->patchJson('api/users/' . $user->id , ['first_name' => $this->faker->firstName])
      ->assertStatus(403);
  }
  /**
   * PATCH api/user/:user
   * @group users
   * @test
   */
  public function authenticated_users_with_permission_can_update_user_profile()
  {
    $user = User::factory()->create();
    $updateUser = User::factory()->make()->toArray();
    Permission::factory()->state(['name' => 'update user profile'])->create();
    $this->user->givePermissionTo('update user profile');
    $this->actingAs($this->user, 'api')
      ->patchJson('api/users/' . $user->id, $updateUser)
      ->assertStatus(200);

    $updatedUser = User::find($user->id);
    $this->assertEquals($updatedUser->first_name, $updateUser['first_name']);
    $this->assertEquals($updatedUser->last_name, $updateUser['last_name']);
    $this->assertEquals($updatedUser->middle_name, $updateUser['middle_name']);
    $this->assertEquals($updatedUser->other_names, $updateUser['other_names']);
    $this->assertEquals($updatedUser->gender_id, $updateUser['gender_id']);
    $this->assertEquals($updatedUser->religion_id, $updateUser['religion_id']);
    $this->assertEquals($updatedUser->email, $updateUser['email']);
    $this->assertEquals($updatedUser->phone, $updateUser['phone']);
    $this->assertEquals($updatedUser->date_of_birth, $updateUser['date_of_birth']);
  }
  /**
   * PATCH api/user/:user
   * @group users
   * @test
   */
  public function authenticated_users_with_permission_can_update_single_user_profile_details()
  {
    $user = User::factory()->create();
    $updateUser = User::factory()->make()->toArray();
    Permission::factory()->state(['name' => 'update user profile'])->create();
    $this->user->givePermissionTo('update user profile');
    $this->actingAs($this->user, 'api')
      ->patchJson('api/users/' . $user->id, ['middle_name' => $this->faker->firstName])
      ->assertStatus(200);
  }

  /**
   * PATCH api/user/:user
   * @group users
   * @test
   */
  public function error_422_thrown_if_no_field_provided_while_updating_profile()
  {
    $user = User::factory()->create();
    Permission::factory()->state(['name' => 'update user profile'])->create();
    $this->user->givePermissionTo('update user profile');
    $this->actingAs($this->user, 'api')
      ->patchJson('api/users/' . $user->id, [])
      ->assertStatus(422);
  }
  /**
   * PATCH api/user/:user
   * @group users
   * @test
   */
  public function error_422_thrown_if_incorrect_date_of_birth_field_provided_while_updating_profile()
  {
    $user = User::factory()->create();
    Permission::factory()->state(['name' => 'update user profile'])->create();
    $this->user->givePermissionTo('update user profile');
    $this->actingAs($this->user, 'api')
      ->patchJson('api/users/' . $user->id, ['date_of_birth' => Carbon::create(2000)->format('m-d-Y')])
      ->assertStatus(422);
  }

  /**
   * PATCH api/user/:user
   * @group users
   * @test
   */
  public function error_422_thrown_if_empty_first_name_is_provided_while_updating_profile()
  {
    $user = User::factory()->create();
    Permission::factory()->state(['name' => 'update user profile'])->create();
    $this->user->givePermissionTo('update user profile');
    $this->actingAs($this->user, 'api')
      ->patchJson('api/users/' . $user->id, ['first_name' => ''])
      ->assertStatus(422);
  }

  /**
   * PATCH api/user/:user
   * @group users
   * @test
   */
  public function error_422_thrown_if_empty_last_name_is_provided_while_updating_profile()
  {
    $user = User::factory()->create();
    Permission::factory()->state(['name' => 'update user profile'])->create();
    $this->user->givePermissionTo('update user profile');
    $this->actingAs($this->user, 'api')
      ->patchJson('api/users/' . $user->id, ['last_name' => '','middle_name' => $this->faker->firstName])
      ->assertStatus(422);
  }

}


