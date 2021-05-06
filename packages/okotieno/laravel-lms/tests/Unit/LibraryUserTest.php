<?php


namespace Okotieno\LMS\Tests\Unit;


use App\Models\User;
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
   public function unauthenticated_users_cannot_create_library_user() {
     $this->postJson('api/library-books/users', [])
       ->assertStatus(401);
   }

  /**
   * POST /api/library-books/users
   * @test
   * @group library
   * @group library-users
   */
  public function authenticated_users_without_permission_cannot_create_library_user() {
    $this->actingAs($this->user, 'api')->postJson('api/library-books/users', [])
      ->assertStatus(403);
  }

  /**
   * POST /api/library-books/users
   * @test
   * @group library
   * @group library-users
   */
  public function authenticated_users_with_permission_can_create_library_user() {
    $user = User::factory()->create();
    Permission::factory()->state(['name' => 'create library user'])->create();
    $this->user->givePermissionTo('create library user');
    $this->actingAs($this->user, 'api')->postJson('api/library-books/users', ['user_id' => $user->id])
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message']);
  }
}
