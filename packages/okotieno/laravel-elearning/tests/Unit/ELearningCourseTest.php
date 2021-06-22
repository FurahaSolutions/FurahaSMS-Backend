<?php

namespace Okotieno\ELearning\Tests\Unit;

use Okotieno\ELearning\Models\ELearningCourse;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;

class ELearningCourseTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    $this->eLearningCourse = ELearningCourse::factory()
      ->state(['numbering' => $this->faker->name, 'topics' => []])
      ->make()
      ->toArray();
  }

  private array $eLearningCourse;

  /**
   * POST /api/e-learning/courses
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function unauthenticated_user_cannot_create_course()
  {
    $this->postJson('/api/e-learning/courses', $this->eLearningCourse)
      ->assertStatus(401);

  }

  /**
   * POST /api/e-learning/courses
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function unauthorised_user_cannot_create_course()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', $this->eLearningCourse)
      ->assertStatus(403);
  }

  /**
   * POST /api/e-learning/courses
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function authorised_user_can_create_course()
  {
    Permission::factory()->state(['name' => 'create e-learning course'])->create();
    $this->user->givePermissionTo('create e-learning course');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', $this->eLearningCourse)
      ->assertStatus(200);
  }

  /**
   * POST /api/e-learning/courses
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function should_return_error_422_if_field_not_provided()
  {
    Permission::factory()->state(['name' => 'create e-learning course'])->create();
    $this->user->givePermissionTo('create e-learning course');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', ELearningCourse::factory()->state([
        'unit_id' => '',
        'numbering' => $this->faker->name,
        'topics' => []
      ])->make()->toArray())
      ->assertStatus(422);
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', ELearningCourse::factory()->state([
        'name' => '',
        'numbering' => $this->faker->name,
        'topics' => []
      ])->make()->toArray())
      ->assertStatus(422);
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', ELearningCourse::factory()->state([
        'numbering' => '',
        'topics' => []
      ])->make()->toArray())
      ->assertStatus(422);
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', ELearningCourse::factory()->state([
        'class_level_id' => '',
        'numbering' => $this->faker->name,
        'topics' => []
      ])->make()->toArray())
      ->assertStatus(422);
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', ELearningCourse::factory()->state([
        'unit_level_id' => '',
        'numbering' => $this->faker->name,
        'topics' => []
      ])->make()->toArray())
      ->assertStatus(422);
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', ELearningCourse::factory()->state([
        'description' => '',
        'numbering' => $this->faker->name,
        'topics' => []
      ])->make()->toArray())
      ->assertStatus(422);
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', ELearningCourse::factory()->state([
        'academic_year_id' => '',
        'numbering' => $this->faker->name,
        'topics' => []
      ])->make()->toArray())
      ->assertStatus(422);
    $this->actingAs($this->user, 'api')
      ->postJson('/api/e-learning/courses', ELearningCourse::factory()->state([
        'topics' => '',
        'numbering' => $this->faker->name,
      ])->make()->toArray())
      ->assertStatus(422);
  }

  /**
   * PATCH /api/e-learning/courses/:id
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function unauthenticated_user_cannot_update_course()
  {
    $eLearningId = ELearningCourse::factory()->create()->id;
    $eLearningUpdate = ELearningCourse::factory()
      ->state(['numbering' => $this->faker->name, 'topics' => []])
      ->make()->toArray();
    $this->patchJson('/api/e-learning/courses/' . $eLearningId, $eLearningUpdate)
      ->assertStatus(401);
  }

  /**
   * PATCH /api/e-learning/courses/:id
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function unauthorised_user_cannot_update_course()
  {
    $eLearningId = ELearningCourse::factory()->create()->id;
    $eLearningUpdate = ELearningCourse::factory()
      ->state(['numbering' => $this->faker->name, 'topics' => []])
      ->make()->toArray();
    $this->actingAs($this->user, 'api')->patchJson('/api/e-learning/courses/' . $eLearningId, $eLearningUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/e-learning/courses/:id
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function authorised_user_can_update_course()
  {
    Permission::factory()->state(['name' => 'update e-learning course'])->create();
    $this->user->givePermissionTo('update e-learning course');
    $eLearningId = ELearningCourse::factory()->create()->id;
    $eLearningUpdate = ELearningCourse::factory()
      ->state(['numbering' => $this->faker->name, 'topics' => []])
      ->make()->toArray();
    $this->actingAs($this->user, 'api')->patchJson('/api/e-learning/courses/' . $eLearningId, $eLearningUpdate)
      ->assertStatus(200);
  }

  /**
   * PATCH /api/e-learning/courses/:id
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function should_update_course_after_successful_update()
  {
    Permission::factory()->state(['name' => 'update e-learning course'])->create();
    $this->user->givePermissionTo('update e-learning course');
    $eLearning = ELearningCourse::factory()->create();
    $eLearningUpdate = ELearningCourse::factory()
      ->state(['numbering' => $this->faker->name, 'topics' => []])
      ->make();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/e-learning/courses/' . $eLearning->id, $eLearningUpdate->toArray())
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $this->assertEquals($eLearningUpdate->name, ELearningCourse::find($eLearning->id)->name);
    $this->assertEquals($eLearningUpdate->class_level_id, ELearningCourse::find($eLearning->id)->class_level_id);
    $this->assertEquals($eLearningUpdate->unit_level_id, ELearningCourse::find($eLearning->id)->unit_level_id);
    $this->assertEquals($eLearningUpdate->academic_year_id, ELearningCourse::find($eLearning->id)->academic_year_id);
    $this->assertEquals($eLearningUpdate->unit_id, ELearningCourse::find($eLearning->id)->unit_id);
    $this->assertEquals($eLearningUpdate->description, ELearningCourse::find($eLearning->id)->description);
  }

  /**
   * DELETE /api/e-learning/courses/:id
   * @group delete-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function unauthenticated_user_cannot_delete_course()
  {
    $eLearningId = ELearningCourse::factory()->create()->id;
    $this->deleteJson('/api/e-learning/courses/' . $eLearningId)
      ->assertStatus(401);
  }

  /**
   * DELETE /api/e-learning/courses/:id
   * @group delete-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function unauthorised_user_cannot_delete_course()
  {
    $eLearningId = ELearningCourse::factory()->create()->id;
    $this->actingAs($this->user, 'api')->deleteJson('/api/e-learning/courses/' . $eLearningId)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/e-learning/courses/:id
   * @group delete-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function authorised_user_can_delete_course()
  {
    Permission::factory()->state(['name' => 'delete e-learning course'])->create();
    $this->user->givePermissionTo('delete e-learning course');
    $eLearningId = ELearningCourse::factory()->create()->id;
    $this->actingAs($this->user, 'api')->deleteJson('/api/e-learning/courses/' . $eLearningId)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);

    $this->assertNull(ELearningCourse::find($eLearningId));
  }

  /**
   * GET /api/e-learning/courses
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function unauthenticated_user_cannot_retrieve_courses()
  {
    $this->getJson('/api/e-learning/courses')
      ->assertUnauthorized();

  }

  /**
   * GET /api/e-learning/courses
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function authenticated_user_can_retrieve_courses()
  {
    ELearningCourse::factory()->count(2)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/e-learning/courses')
      ->assertOk();

  }

  /**
   * GET /api/e-learning/courses
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function unauthenticated_user_cannot_retrieve_course()
  {
    $eLearningCourseId = ELearningCourse::factory()->create()->id;
    $this->getJson('/api/e-learning/courses/' . $eLearningCourseId)
      ->assertUnauthorized();

  }

  /**
   * GET /api/e-learning/courses
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function authenticated_user_can_retrieve_course()
  {
    $eLearningCourseId = ELearningCourse::factory()->create()->id;
    ELearningTopic::factory()->state(['e_learning_course_id' => $eLearningCourseId])->create();
    ELearningCourse::factory()->count(2)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/e-learning/courses/' . $eLearningCourseId)
      ->assertOk();

  }

}
