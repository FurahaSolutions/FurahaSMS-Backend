<?php


namespace Okotieno\ELearning\Tests\Unit;


use Okotieno\ELearning\Models\ELearningCourseContent;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;

class ELearningContentTest extends TestCase
{
  /**
   * POST /api/e-learning/courses
   * @group post-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function unauthenticated_user_cannot_create_course_content()
  {
    $content = ELearningCourseContent::factory()->make()->toArray();
    $this->postJson('/api/e-learning/course-content', $content)
      ->assertStatus(401);
  }

  /**
   * POST /api/e-learning/courses
   * @group post-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function unauthorised_user_cannot_create_course_content()
  {
    $content = ELearningCourseContent::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/course-content', $content)
      ->assertStatus(403);
  }

  /**
   * POST /api/e-learning/courses
   * @group post-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function authorised_user_can_create_course_content()
  {
    Permission::factory()->state(['name' => 'upload curriculum content'])->create();
    $this->user->givePermissionTo('upload curriculum content');
    $content = ELearningCourseContent::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/course-content', $content)
      ->assertStatus(201);
  }

  /**
   * DELETE /api/e-learning/courses
   * @group delete-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function unauthenticated_user_cannot_delete_course_content()
  {
    $content = ELearningCourseContent::factory()->create();
    $this->deleteJson("/api/e-learning/course-content/{$content->id}")
      ->assertUnauthorized();
  }

  /**
   * DELETE /api/e-learning/courses
   * @group delete-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function authenticated_user_without_permission_cannot_delete_course_content()
  {
    $content = ELearningCourseContent::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson("/api/e-learning/course-content/{$content->id}", [])
      ->assertForbidden();
  }


  /**
   * DELETE /api/e-learning/courses
   * @group delete-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function authenticated_user_with_permission_can_delete_course_content()
  {
    Permission::factory()->state(['name' => 'delete e-learning course content'])->create();
    $this->user->givePermissionTo('delete e-learning course content');
    $content = ELearningCourseContent::factory()->create();
    $data = [
      "study_material_id" => $content->study_material_id,
      'e_learning_topic_id' => $content->e_learning_topic_id
    ];
    $this->actingAs($this->user, 'api')
      ->deleteJson("/api/e-learning/course-content/{$content->id}", $data)
      ->assertOk()
      ->assertJsonStructure(['saved', 'message']);
  }

  /**
   * PATCH /api/e-learning/courses
   * @group patch-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function unauthenticated_user_cannot_update_course_content()
  {
    $content = ELearningCourseContent::factory()->create();
    $this->patchJson("/api/e-learning/course-content/{$content->id}")
      ->assertUnauthorized();
  }

  /**
   * PATCH /api/e-learning/courses
   * @group patch-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function user_without_permission_cannot_update_course_content()
  {
    $content = ELearningCourseContent::factory()->create();
    $this->actingAs($this->user, 'api')
      ->patchJson("/api/e-learning/course-content/{$content->id}")
      ->assertForbidden();
  }

  /**
   * PATCH /api/e-learning/courses
   * @group patch-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function error_422_if_title_not_provided_on_update_course_content()
  {
    Permission::factory()->state(['name' => 'update e-learning course content'])->create();
    $this->user->givePermissionTo('update e-learning course content');
    $content = ELearningCourseContent::factory()->create();
    $this->actingAs($this->user, 'api')
      ->patchJson("/api/e-learning/course-content/{$content->id}")
      ->assertStatus(422);
  }

  /**
   * PATCH /api/e-learning/courses
   * @group patch-request
   * @group course-content
   * @group e-learning
   * @test
   * */
  public function user_with_permission_can_update_course_content()
  {
    Permission::factory()->state(['name' => 'update e-learning course content'])->create();
    $this->user->givePermissionTo('update e-learning course content');
    $content = ELearningCourseContent::factory()->create();
    $this->actingAs($this->user, 'api')
      ->patchJson("/api/e-learning/course-content/{$content->id}", ['title' => $this->faker->sentence(3)])
      ->assertOk();
  }

}
