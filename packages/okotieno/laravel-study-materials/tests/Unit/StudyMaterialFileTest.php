<?php

namespace Okotieno\StudyMaterials\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;

class StudyMaterialFileTest extends TestCase
{
  /**
   * GET /api/study-materials/document-uploads
   * @group study-materials
   * @test
   */

  public function unauthenticated_users_cannot_access_study_material_files()
  {
    $this->getJson('api/study-materials/document-uploads')
      ->assertUnauthorized();
  }

  /**
   * GET /api/study-materials/document-uploads
   * @group study-materials
   * @test
   */

  public function error_422_if_file_path_not_provided_while_retrieving_study_material_file()
  {
    $this->actingAs($this->user, 'api')
      ->getJson('api/study-materials/document-uploads')
      ->assertStatus(422);
  }

  /**
   * GET /api/study-materials/document-uploads
   * @group study-materials
   * @test
   * @param $filePath
   */

  public function authenticated_users_can_retrieve_study_material()
  {
    $file = UploadedFile::fake()->create('invalid file.jpg');
    $filePath = Storage::putFile('avatars', $file);
    $this->actingAs($this->user, 'api')
      ->getJson('api/study-materials/document-uploads?file_path=' . $filePath)
      ->assertOk();
  }

  /**
   * POST /api/study-materials/document-uploads
   * @group study-materials
   * @test
   */

  public function unauthenticated_users_cannot_create_study_material_files()
  {
    $this->postJson('api/study-materials/document-uploads')
      ->assertUnauthorized();
  }

  /**
   * POST /api/study-materials/document-uploads
   * @group study-materials
   * @test
   */

  public function users_without_permission_cannot_create_study_material_files()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('api/study-materials/document-uploads')
      ->assertForbidden();
  }

  /**
   * POST /api/study-materials/document-uploads
   * @group study-materials
   * @test
   */

  public function error_422_if_pdf_file_not_provided()
  {
    Permission::factory()->state(['name' => 'upload study materials'])->create();
    $this->user->givePermissionTo('upload study materials');
    $this->actingAs($this->user, 'api')
      ->postJson('api/study-materials/document-uploads', [])
      ->assertStatus(422);
  }

  /**
   * POST /api/study-materials/document-uploads
   * @group study-materials
   * @test
   */

  public function users_with_permission_can_create_study_material_files()
  {
    $file = UploadedFile::fake()->create('invalid file.pdf');
    Permission::factory()->state(['name' => 'upload study materials'])->create();
    $this->user->givePermissionTo('upload study materials');
    $this->actingAs($this->user, 'api')
      ->postJson('api/study-materials/document-uploads', ['pdfFile' => $file])
      ->assertOk();
  }


}
