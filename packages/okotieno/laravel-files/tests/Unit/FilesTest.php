<?php

namespace Okotieno\Files\Tests\Unit;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;


class FilesTest extends TestCase
{

  /**
   * GET /api/files
   * @group files
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_files()
  {
    $this->getJson('/api/files')
      ->assertStatus(401);
  }

  /**
   * POST /api/files
   * @group files
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_files()
  {
    Storage::fake('avatars');
    $this->postJson('/api/files', [
      'profilePicture' => UploadedFile::fake()->image('avatar.jpg')
    ])
      ->assertStatus(401);
  }

  /**
   * POST /api/files
   * @group files
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_upload_profile_picture()
  {
    Storage::fake('avatars');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/files?userId=' . User::factory()->create()->id, [
        'profilePicture' => UploadedFile::fake()->image('avatar.jpg')
      ])
      ->assertStatus(403);
  }

  /**
   * POST /api/files
   * @group files
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_upload_profile_picture()
  {
    Permission::factory()->state(['name' => 'upload profile picture'])->create();
    $this->user->givePermissionTo('upload profile picture');

    Storage::fake('avatars');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/files?userId=' . User::factory()->create()->id, [
        'profilePicture' => UploadedFile::fake()->image('avatars.jpg')
      ])
      ->assertStatus(201);
  }
}



