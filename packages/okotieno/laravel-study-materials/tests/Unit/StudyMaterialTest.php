<?php

namespace Okotieno\StudyMaterials\Tests\Unit;

use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\StudyMaterials\Models\StudyMaterial;
use Okotieno\StudyMaterials\Models\StudyMaterialDoc;
use Tests\TestCase;

class StudyMaterialTest extends TestCase
{
  /**
   * GET /api/study-materials
   * @group study-materials
   * @test
   */

  public function unauthenticated_users_cannot_access_study_materials()
  {
    $this->getJson('api/study-materials')
      ->assertUnauthorized();
  }

  /**
   * GET /api/study-materials
   * @group study-materials
   * @test
   */

  public function authenticated_users_can_access_study_materials()
  {
    $studyMaterial = StudyMaterial::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('api/study-materials')
      ->assertOk()
      ->assertJsonStructure([['id', 'title']])
      ->assertJsonFragment(['title' => $studyMaterial->title]);
  }
  /**
   * GET /api/study-materials
   * @group study-materials
   * @test
   */

  public function unauthenticated_users_cannot_access_study_material()
  {
    $studyMaterial = StudyMaterial::factory()->create();
    $this->getJson('api/study-materials/'.$studyMaterial->id)
      ->assertUnauthorized();
  }

  /**
   * GET /api/study-materials
   * @group study-materials
   * @test
   */

  public function authenticated_users_can_access_study_material()
  {
    $studyMaterial = StudyMaterial::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('api/study-materials/'.$studyMaterial->id)
      ->assertOk()
      ->assertJsonStructure(['id', 'title'])
      ->assertJsonFragment(['title' => $studyMaterial->title]);
  }

  /**
   * GET /api/study-materials
   * @group study-materials
   * @test
   */

  public function unauthenticated_users_cannot_create_study_material()
  {
    $this->postJson('api/study-materials', [])
      ->assertUnauthorized();
  }
  /**
   * GET /api/study-materials
   * @group study-materials
   * @test
   */

  public function users_without_permission_cannot_create_study_material()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('api/study-materials', [])
      ->assertForbidden();
  }

  /**
   * GET /api/study-materials
   * @group study-materials
   * @test
   */

  public function error_422_if_docId_not_provided()
  {
    Permission::factory()->state(['name' => 'upload study materials'])->create();
    $this->user->givePermissionTo('upload study materials');
    $this->actingAs($this->user, 'api')
      ->postJson('api/study-materials', [])
      ->assertStatus(422);
  }

  /**
   * POST /api/study-materials
   * @group study-materials
   * @test
   */
  public function users_with_permission_can_create_study_material()
  {
    Permission::factory()->state(['name' => 'upload study materials'])->create();
    $this->user->givePermissionTo('upload study materials');

    $this->actingAs($this->user, 'api')
      ->postJson('api/study-materials', [
        'docId' => StudyMaterialDoc::factory()->create()->id,
        'title' => $this->faker->sentence(3)
      ])
      ->assertCreated()
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'title']]);
  }


}
