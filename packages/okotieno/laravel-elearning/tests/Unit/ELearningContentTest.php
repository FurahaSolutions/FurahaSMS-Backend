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

}
