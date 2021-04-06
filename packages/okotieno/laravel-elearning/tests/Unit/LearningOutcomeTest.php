<?php

namespace Okotieno\ELearning\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\ELearning\Models\TopicLearningOutcome;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;


class LearningOutcomeTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $learningOutcome;


  protected function setUp(): void
  {
    parent::setUp();
    $this->learningOutcome = TopicLearningOutcome::factory()->make()->toArray();
  }

  /**
   * POST /api/e-learning/course-content/topics/1/learning-outcomes
   * @group learning-outcome
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_learning_outcome()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes';
    $this->postJson($url, $this->learningOutcome)
      ->assertStatus(401);

  }

  /**
   * POST /api/e-learning/course-content/topics/1/learning-outcomes
   * @group learning-outcome
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_learning_outcome()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes';
    $this->actingAs($this->user, 'api')->postJson($url, $this->learningOutcome)
      ->assertStatus(403);
  }

  /**
   * POST /api/e-learning/course-content/topics/1/learning-outcomes
   * @group learning-outcome
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_learning_outcome()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes';
    Permission::factory()->state(['name' => 'create learning outcome'])->create();
    $this->user->givePermissionTo('create learning outcome');
    $response = $this->actingAs($this->user, 'api')->postJson($url, $this->learningOutcome);
    $response->assertStatus(201);
  }

  /**
   * POST /api/e-learning/course-content/topics/1/learning-outcomes
   * @group learning-outcome
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_description_not_provided()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes';
    $this->learningOutcome['description'] = '';
    Permission::factory()->state(['name' => 'create learning outcome'])->create();
    $this->user->givePermissionTo('create learning outcome');
    $this->actingAs($this->user, 'api')->postJson($url, $this->learningOutcome)
      ->assertStatus(422);
  }

  /**
   * POST /api/e-learning/course-content/topics/1/learning-outcomes
   * @group learning-outcome
   * @test
   * @group post-request
   * @return void
   */
  public function learning_outcome_should_exist_after_successful_call()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes';
    Permission::factory()->state(['name' => 'create learning outcome'])->create();
    $this->user->givePermissionTo('create learning outcome');
    $this->actingAs($this->user, 'api')->postJson($url, $this->learningOutcome)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'description']]);
    $learningOutcome = TopicLearningOutcome::where('description', $this->learningOutcome['description'])
      ->first();
    $this->assertNotNull($learningOutcome);
  }


  /**
   * PATCH /api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_learning_outcome()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->create();
    $learningOutcomeUpdate = TopicLearningOutcome::factory()->make()->toArray();
    $res = $this->patchJson($url . $learningOutcome->id, $learningOutcomeUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_update_learning_outcome()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->create();
    $learningOutcomeUpdate = TopicLearningOutcome::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson($url . $learningOutcome->id, $learningOutcomeUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_learning_outcome()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->for($topic)->create();
    $learningOutcomeUpdate = TopicLearningOutcome::factory()->make()->toArray();
    $this->user->permissions()->create(['name' => 'update learning outcome']);
    $this->actingAs($this->user, 'api')
      ->patchJson($url . $learningOutcome->id, $learningOutcomeUpdate)
      ->assertStatus(200);
  }

  /**
   * PATCH /api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_description_not_provided_on_update()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->create();
    $learningOutcomeUpdate = TopicLearningOutcome::factory()->state(['description' => ''])->make()->toArray();
    $this->user->permissions()->create(['name' => 'update learning outcome']);
    $this->actingAs($this->user, 'api')
      ->patchJson($url . $learningOutcome->id, $learningOutcomeUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @test
   * @group patch-request
   * @return void
   */
  public function learning_outcome_should_be_updated_after_successful_call()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->for($topic)->create();
    $learningOutcomeUpdate = TopicLearningOutcome::factory()->make()->toArray();
    $this->user->permissions()->create(['name' => 'update learning outcome']);
    $this->actingAs($this->user, 'api')
      ->patchJson($url . $learningOutcome->id, $learningOutcomeUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'description']]);
  }

  /**
   * DELETE/api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_learning_outcome()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->create();
    $this->deleteJson($url . $learningOutcome->id)
      ->assertStatus(401);

  }

  /**
   * DELETE/api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_learning_outcome()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson($url . $learningOutcome->id)
      ->assertStatus(403);
  }

  /**
   * DELETE/api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_learning_outcome()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->for($topic)->create();
    $this->user->permissions()->create(['name' => 'delete learning outcome']);
    $this->actingAs($this->user, 'api')
      ->deleteJson($url . $learningOutcome->id)
      ->assertStatus(200);
  }

  /**
   * DELETE/api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @test
   * @group delete-request
   * @return void
   */
  public function learning_outcome_should_be_deleted_after_successful_call()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->for($topic)->create();
    $this->user->permissions()->create(['name' => 'delete learning outcome']);
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson($url . $learningOutcome->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(TopicLearningOutcome::find($learningOutcome->id));
  }

  /**
   * PATCH /api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @test
   * @group delete-request
   * @return void
   */
  public function should_return_404_error_if_learning_outcome_does_not_exist_for_topic_on_update()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->create();
    $learningOutcomeUpdate = TopicLearningOutcome::factory()->make()->toArray();
    $this->user->permissions()->create(['name' => 'update learning outcome']);
    $this->actingAs($this->user, 'api')
      ->patchJson($url . $learningOutcome->id, $learningOutcomeUpdate)
      ->assertStatus(404);
  }
  /**
   * DELETE/api/e-learning/course-content/topics/1/learning-outcomes/{id}
   * @group learning-outcome
   * @test
   * @group delete-request
   * @return void
   */
  public function should_return_404_error_if_learning_outcome_does_not_exist_for_topic_on_delete()
  {
    $topic = ELearningTopic::factory()->create();
    $url = 'api/e-learning/course-content/topics/'.$topic->id.'/learning-outcomes/';
    $learningOutcome = TopicLearningOutcome::factory()->create();
    $this->user->permissions()->create(['name' => 'delete learning outcome']);
    $this->actingAs($this->user, 'api')
      ->deleteJson($url . $learningOutcome->id)
      ->assertStatus(404);
  }
}



