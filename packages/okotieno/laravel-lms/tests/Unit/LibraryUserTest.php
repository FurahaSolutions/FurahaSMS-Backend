<?php


namespace Okotieno\LMS\Tests\Unit;


use App\Models\User;
use Okotieno\LMS\Models\LibraryUser;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;

class LibraryUserTest extends TestCase
{
  /**
   * POST /api/library-books/users
   * @test
   * @group library
   * @group library-users
   */
  public function unauthenticated_users_cannot_create_library_user()
  {
    $this->postJson('api/library-books/users', [])
      ->assertStatus(401);
  }

  /**
   * POST /api/library-books/users
   * @test
   * @group library
   * @group library-users
   */
  public function authenticated_users_without_permission_cannot_create_library_user()
  {
    $this->actingAs($this->user, 'api')->postJson('api/library-books/users', [])
      ->assertStatus(403);
  }

  /**
   * POST /api/library-books/users
   * @test
   * @group library
   * @group library-users
   */
  public function authenticated_users_with_permission_can_create_library_user()
  {
    $user = User::factory()->create();
    Permission::factory()->state(['name' => 'create library user'])->create();
    $this->user->givePermissionTo('create library user');
    $this->actingAs($this->user, 'api')->postJson('api/library-books/users', ['user_id' => $user->id])
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message']);
  }

  /**
   * POST /api/library-books/users
   * @test
   * @group library
   * @group library-users
   */
  public function authenticated_users_can_retrieve_library_user()
  {
    $user = User::factory()->create();
    $libraryUser = LibraryUser::factory([
      'user_id' => $user->id
    ])->create();

    $this->actingAs($this->user, 'api')
      ->getJson('api/library-books/users/' . $libraryUser->user_id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'firstName','lastName', 'libraryUserId']);
  }

  /**
   * PATCH /api/library-books/users
   * @test
   * @group library
   * @group library-users
   */
  public function authenticated_users_with_permission_can_suspend_library_user()
  {
    Permission::factory()->state(['name' => 'suspend library user'])->create();
    $this->user->givePermissionTo('suspend library user');
    $user = User::factory()->create();
    $libraryUser = LibraryUser::factory([
      'user_id' => $user->id
    ])->create();
    $this->actingAs($this->user, 'api')
      ->patchJson('api/library-books/users/' . $libraryUser->user_id, ['suspended' => true])
      ->assertStatus(200);

    $this->assertTrue(User::find($user->id)->library_suspended);
  }

  /**
   * PATCH /api/library-books/users
   * @test
   * @group library
   * @group library-users
   */
  public function authenticated_users_with_permission_can_unsuspend_library_user()
  {
    Permission::factory()->state(['name' => 'unsuspend library user'])->create();
    $this->user->givePermissionTo('unsuspend library user');
    $user = User::factory()->create();
    $libraryUser = LibraryUser::factory([
      'user_id' => $user->id
    ])->create();
    $this->actingAs($this->user, 'api')
      ->patchJson('api/library-books/users/' . $libraryUser->user_id, ['suspended' => false])
      ->assertStatus(200);

    $this->assertFalse(User::find($user->id)->library_suspended);
  }
}
