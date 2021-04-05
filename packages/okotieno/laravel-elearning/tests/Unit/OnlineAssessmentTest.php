<?php

namespace Okotieno\ELearning\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolExams\Models\OnlineAssessment;
use Tests\TestCase;

class OnlineAssessmentTest extends TestCase
{

  use WithFaker;
  use DatabaseTransactions;

  private $name;

  protected function setUp(): void
  {
    parent::setUp();
    $this->name = $this->faker->name;
  }

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
    Permission::factory()->state(['name' => 'create online assessment']);
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
    Permission::factory()->state(['name' => 'create online assessment']);
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
    Permission::factory()->state(['name' => 'create online assessment']);
    $topic = ELearningTopic::factory()->create();
    $this->user->givePermissionTo('create online assessment');
    $this->user->permissions()->create(['name' => 'create online assessment']);
    $this->actingAs($this->user, 'api')
      ->postJson('api/e-learning/course-content/topics/' . $topic->id . '/online-assessments', $onlineAssessment)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id']]);

    $this->assertNotEmpty($topic->onlineAssessments->toArray());
  }

}
