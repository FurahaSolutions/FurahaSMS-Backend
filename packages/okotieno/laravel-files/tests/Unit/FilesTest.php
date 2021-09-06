<?php

namespace Okotieno\Files\Tests\Unit;

use App\Models\User;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Okotieno\Files\Models\FileDocument;
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
  public function authenticated_users_can_download_profile_picture()
  {
    Storage::fake('local');
    $file = File::create('profile_pic' . rand(1000000, 9999999999), 100);
    $user = User::factory()->create();
    $user->profilePics()->create([
      'file_document_id' => FileDocument::factory()->state([
        'file_path' => $file->store('profile_pics', 'local')
      ])->create()->id,
      'model' => User::class,
      'model_id' => $user->id
    ]);
    $this->actingAs($this->user, 'api')
      ->get('/api/files?profilePicture=true&userId=' . $user->id)
      ->assertStatus(200)
      ->assertDownload();
  }

  /**
   * POST /api/files
   * @group files
   * @group get-request
   * @test
   * @return void
   */

  public function authenticated_users_can_download_file_by_document_id()
  {
    Storage::fake('local');
    $file = File::create('file' . rand(1000000, 9999999999), 100);
    $fileDocument = FileDocument::factory()->state([
      'file_path' => $file->store('profile_pics', 'local')
    ])->create();

    $this->actingAs($this->user, 'api')
      ->get('/api/files/' . $fileDocument->id)
      ->assertStatus(200)
      ->assertDownload();
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



