<?php

namespace Okotieno\ELearning\Tests\Unit;

use Carbon\Carbon;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolExams\Models\ExamPaper;
use Okotieno\SchoolExams\Models\ExamPaperQuestion;
use Okotieno\SchoolExams\Models\OnlineAssessment;
use Tests\TestCase;

class OnlineAssessmentTest extends TestCase
{

  protected function setUp(): void
  {
    parent::setUp();
    $this->name = $this->faker->name;
  }

  private $name;

  /**
   * User should be able to create E-Learning Online Assessment
   * @group post-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */
  public function unauthenticated_users_should_not_create_new_online_assessment()
  {
    $this->postJson('api/e-learning/course-content/topics/1/online-assessments', [])
      ->assertStatus(401);
  }

  /**
   * User without Permission should not be able to create E-Learning Online Assessment
   * @group post-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */
  public function test_without_permission_user_cannot_create_online_assessment()
  {
    $topic = ELearningTopic::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/e-learning/course-content/topics/' . $topic->id . '/online-assessments', [])
      ->assertStatus(403);
  }

  /**
   * User should be able to create E-Learning Online Assessment
   * @group post-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @group current
   * @test
   * */

  public function with_permission_user_can_create_online_assessment()
  {
    Permission::factory()->state(['name' => 'create online assessment'])->create();
    $topic = ELearningTopic::factory()->create();
    $this->user->givePermissionTo('create online assessment');
    $onlineAssessment = OnlineAssessment::factory()->state([
      'exam_paper_id' => null,
      'e_learning_topic_id' => null,
      'name' => $this->faker->name
    ])->make()->toArray();

    $this->actingAs($this->user, 'api')
      ->postJson('api/e-learning/course-content/topics/' . $topic->id . '/online-assessments', $onlineAssessment)
      ->assertStatus(201);
  }

  /**
   * Error 422 is thrown if description is not provided
   * @group post-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */
  public function throws_error_if_name_is_not_provided()
  {
    Permission::factory()->state(['name' => 'create online assessment'])->create();
    $topic = ELearningTopic::factory()->create();
    $this->user->givePermissionTo('create online assessment');
    $this->actingAs($this->user, 'api')
      ->postJson('api/e-learning/course-content/topics/' . $topic->id . '/online-assessments', [])
      ->assertStatus(422);
  }

  /**
   * Error 422 is thrown if description is not provided
   * @group post-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */
  public function should_create_online_assessment()
  {
    $assessmentName = $this->faker->name;
    $onlineAssessment = OnlineAssessment::factory()->state([
      'exam_paper_id' => null,
      'e_learning_topic_id' => null,
      'name' => $assessmentName
    ])->make()->toArray();
    Permission::factory()->state(['name' => 'create online assessment'])->create();
    $topic = ELearningTopic::factory()->create();
    $this->user->givePermissionTo('create online assessment');
    $this->user->permissions()->create(['name' => 'create online assessment']);
    $this->actingAs($this->user, 'api')
      ->postJson('api/e-learning/course-content/topics/' . $topic->id . '/online-assessments', $onlineAssessment)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id']]);

    $this->assertNotEmpty($topic->onlineAssessments->toArray());
  }

  /**
   * PATCH /api/e-learning/course-content/topics/:topic/online-assessments/:online-assessment
   * @group patch-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */

  public function unauthenticated_users_cannot_update_online_assessment()
  {
    $onlineAssessment = OnlineAssessment::factory()->create();
    $onlineAssessmentUpdate = OnlineAssessment::factory()->make()->toArray();
    $url = 'api/e-learning/course-content/topics/' . $onlineAssessment->eLearningTopic->id . '/online-assessments/' . $onlineAssessment->id;
    $this->patchJson($url, $onlineAssessmentUpdate)->assertUnauthorized();
  }

  /**
   * PATCH /api/e-learning/course-content/topics/:topic/online-assessments/:online-assessment
   * @group patch-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */

  public function authenticated_users_without_permission_cannot_update_online_assessment()
  {
    $onlineAssessment = OnlineAssessment::factory()->create();
    $onlineAssessmentUpdate = OnlineAssessment::factory()->make()->toArray();
    $url = 'api/e-learning/course-content/topics/' . $onlineAssessment->eLearningTopic->id . '/online-assessments/' . $onlineAssessment->id;
    $this->actingAs($this->user, 'api')
      ->patchJson($url, $onlineAssessmentUpdate)
      ->assertForbidden();
  }

  /**
   * PATCH /api/e-learning/course-content/topics/:topic/online-assessments/:online-assessment
   * @group patch-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */

  public function authenticated_users_with_permission_can_update_online_assessment()
  {
    Permission::factory()->state(['name' => 'update online assessment'])->create();
    $this->user->givePermissionTo('update online assessment');
    $onlineAssessment = OnlineAssessment::factory()->create();
    $onlineAssessmentUpdate = OnlineAssessment::factory()->make()->toArray();
    $onlineAssessmentUpdate['name'] = $onlineAssessmentUpdate['exam_paper_name'];
    $url = 'api/e-learning/course-content/topics/' . $onlineAssessment->eLearningTopic->id . '/online-assessments/' . $onlineAssessment->id;
    $this->actingAs($this->user, 'api')
      ->patchJson($url, $onlineAssessmentUpdate)
      ->assertOk();
  }

  /**
   * GET /api/e-learning/online-assessment/:onlineAssessment
   * @group get-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */

  public function authenticated_users_can_retrieve_online_assessment()
  {
    $onlineAssessment = OnlineAssessment::factory()->create();
    $url = '/api/e-learning/online-assessments/' . $onlineAssessment->id;
    $this->actingAs($this->user, 'api')
      ->getJson($url)
      ->assertOk()
      ->assertJsonFragment(['id' => $onlineAssessment->id]);
  }

  /**
   * GET /api/e-learning/online-assessment/:onlineAssessment
   * @group get-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */

  public function authenticated_users_can_retrieve_online_assessment_with_questions()
  {
    $examPaper = ExamPaper::factory()->create();
    ExamPaperQuestion::factory()
      ->count(3)
      ->state(['exam_paper_id' => $examPaper->id])
      ->create();
    $onlineAssessment = OnlineAssessment::factory()
      ->state(['exam_paper_id' => $examPaper->id])
      ->create();
    $url = '/api/e-learning/online-assessments/' . $onlineAssessment->id . '?withQuestions=true';
    $this->actingAs($this->user, 'api')
      ->getJson($url)
      ->assertOk()
      ->assertJsonStructure(['id', 'questions' => [['id']]])
      ->assertJsonFragment(['id' => $onlineAssessment->id]);
  }

  /**
   * DELETE /api/course-content/topics/:eLearningTopicId/online-assessments
   * @test
   * @group academic
   * @group e-learning
   * @group online-assessment
   */
  public function unauthenticated_users_cannot_delete_online_assessment()
  {
    $onlineAssessment = OnlineAssessment::factory()->create();
    $url = 'api/e-learning/course-content/topics/' . $onlineAssessment->e_learning_topic_id . '/online-assessments/' . $onlineAssessment->id;
    $this->deleteJson($url)->assertUnauthorized();

  }

  /**
   * DELETE /api/course-content/topics/:eLearningTopicId/online-assessments
   * @test
   * @group academic
   * @group e-learning
   * @group online-assessment
   */

  public function authenticated_users_without_permission_cannot_delete_online_assessment()
  {

    $onlineAssessment = OnlineAssessment::factory()->create();
    $url = 'api/e-learning/course-content/topics/' . $onlineAssessment->e_learning_topic_id . '/online-assessments/' . $onlineAssessment->id;
    $this->actingAs($this->user, 'api')
      ->deleteJson($url)
      ->assertForbidden();

  }

  /**
   * DELETE /api/course-content/topics/:eLearningTopicId/online-assessments
   * @test
   * @group academic
   * @group e-learning
   * @group online-assessment
   */

  public function authenticated_users_with_permission_can_delete_online_assessment()
  {
    Permission::factory()->state(['name' => 'delete online assessment'])->create();
    $this->user->givePermissionTo('delete online assessment');
    $onlineAssessment = OnlineAssessment::factory()->create();
    $url = 'api/e-learning/course-content/topics/' . $onlineAssessment->e_learning_topic_id . '/online-assessments/' . $onlineAssessment->id;
    $this->actingAs($this->user, 'api')
      ->deleteJson($url)
      ->assertOk()
      ->assertJsonStructure(['saved', 'message']);

  }

  /**
   * DELETE /api/course-content/topics/:eLearningTopicId/online-assessments
   * @test
   * @group academic
   * @group e-learning
   * @group online-assessment
   */
  public function user_cannot_start_exams_before_available_time()
  {
    $availableAt = Carbon::tomorrow();
    $onlineAssessment = OnlineAssessment::factory()->state(['available_at' => $availableAt])->create();
    $url = 'api/e-learning/online-assessments/' . $onlineAssessment->id.'?withQuestions=true';
    $this->actingAs($this->user, 'api')->getJson($url)->assertForbidden();

  }

  /**
   * DELETE /api/course-content/topics/:eLearningTopicId/online-assessments
   * @test
   * @group academic
   * @group e-learning
   * @group online-assessment
   */
  public function user_with_permission_to_update_online_assessment_can_retrieve()
  {
    Permission::factory()->state(['name' => 'update online assessment'])->create();
    $this->user->givePermissionTo('update online assessment');
    $availableAt = Carbon::tomorrow();
    $onlineAssessment = OnlineAssessment::factory()->state(['available_at' => $availableAt])->create();
    $url = 'api/e-learning/online-assessments/' . $onlineAssessment->id.'?withQuestions=true';
    $this->actingAs($this->user, 'api')->getJson($url)->assertOk();

  }


}
