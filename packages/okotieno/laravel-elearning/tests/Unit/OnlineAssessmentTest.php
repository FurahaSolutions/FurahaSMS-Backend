<?php

namespace Okotieno\ELearning\Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\ELearning\Models\ELearningTopic;
use Tests\TestCase;

class OnlineAssessmentTest extends TestCase
{

  use WithFaker;
  use DatabaseTransactions;


  private $user;
  private $name;

  protected function setUp(): void
  {
    parent::setUp();
    $this->user = User::factory()->create(['email' => $this->faker->email]);
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
   * @test
   * */

  public function with_permission_user_can_create_online_assessment()
  {

    $topic = ELearningTopic::factory()->create();
    $this->user->permissions()->create(['name' => 'create online assessment']);
    $response = $this->actingAs($this->user, 'api')
      ->postJson('api/e-learning/course-content/topics/' . $topic->id . '/online-assessments', [
        'description' => $this->name
      ]);
    $response->assertStatus(201);
  }

  /**
   * Error 422 is thrown if description is not provided
   * @group post-request
   * @group academic
   * @group e-learning
   * @group online-assessment
   * @test
   * */
  public function throws_error_if_description_is_not_provided()
  {
    $topic = ELearningTopic::factory()->create();
    $this->user->permissions()->create(['name' => 'create online assessment']);
    $response = $this->actingAs($this->user, 'api')
      ->postJson('api/e-learning/course-content/topics/' . $topic->id . '/online-assessments', []);
//     echo $response->content();
    $response->assertStatus(422);
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
    $topic = ELearningTopic::factory()->create();
    $this->user->permissions()->create(['name' => 'create online assessment']);
    $response = $this->actingAs($this->user, 'api')
      ->postJson('api/e-learning/course-content/topics/' . $topic->id . '/online-assessments', [
        'description' => $this->name
      ]);
    echo $response->content();
    $response->assertStatus(201);
  }

}
