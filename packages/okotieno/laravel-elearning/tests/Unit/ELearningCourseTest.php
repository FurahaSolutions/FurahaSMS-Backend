<?php

namespace Okotieno\ELearning\Tests\Unit;

use Okotieno\ELearning\Models\ELearningCourse;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;

class ELearningCourseTest extends TestCase
{

  private array $eLearningCourse;

  protected function setUp(): void
  {
    parent::setUp();
    $this->eLearningCourse = ELearningCourse::factory()
      ->state(['numbering' => $this->faker->name,'topics' => []])
      ->make()
      ->toArray();
  }

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
      ->postJson('/api/e-learning/courses',  $this->eLearningCourse)
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
   * POST /api/e-learning/courses
   * @group post-request
   * @group course
   * @group e-learning
   * @test
   * */
  public function unauthenticated_user_cannot_update_course()
  {
    $this->patchJson('/api/e-learning/courses', $this->eLearningCourse)
      ->assertStatus(401);
  }

}
