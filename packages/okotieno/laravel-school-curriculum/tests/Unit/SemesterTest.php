<?php

namespace Okotieno\SchoolCurriculum\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\Semester;
use Tests\TestCase;


class SemesterTest extends TestCase
{

  private $semester;


  protected function setUp(): void
  {
    parent::setUp();
    $this->semester = Semester::factory()->make()->toArray();
  }

  /**
   * GET /api/curriculum/semesters
   * @group curriculum
   * @group semester
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_semesters()
  {
    $this->getJson('/api/curriculum/semesters', $this->semester)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/semesters
   * @group curriculum
   * @group semester
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_semesters()
  {
    Semester::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/semesters', $this->semester)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/curriculum/semesters/:id
   * @group curriculum
   * @group semester
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_semester()
  {
    $semester = Semester::factory()->create();
    $this->getJson('/api/curriculum/semesters/' . $semester->id, $this->semester)
      ->assertStatus(401);

  }

  /**
   * GET /api/curriculum/semesters/:id
   * @group curriculum
   * @group semester
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_semester()
  {
    $semester = Semester::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/curriculum/semesters/' . $semester->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }


  /**
   * POST /api/curriculum/semesters
   * @group curriculum
   * @group semester
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_semester()
  {
    $this->postJson('/api/curriculum/semesters', $this->semester)
      ->assertStatus(401);

  }

  /**
   * POST /api/curriculum/semesters
   * @group curriculum
   * @group semester
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_semester()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/semesters', $this->semester)
      ->assertStatus(403);
  }

  /**
   * POST /api/curriculum/semesters
   * @group curriculum
   * @group semester
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_semester()
  {
    Permission::factory()->state(['name' => 'create semester'])->create();
    $this->user->givePermissionTo('create semester');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/curriculum/semesters', $this->semester)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * POST /api/curriculum/semesters
   * @group curriculum
   * @group semester
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->semester['name'] = '';
    Permission::factory()->state(['name' => 'create semester'])->create();
    $this->user->givePermissionTo('create semester');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/semesters', $this->semester)
      ->assertStatus(422);
  }


  /**
   * POST /api/curriculum/semesters
   * @group curriculum
   * @group semester
   * @test
   * @group post-request
   * @return void
   */
  public function semester_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create semester'])->create();
    $this->user->givePermissionTo('create semester');
    $this->actingAs($this->user, 'api')->postJson('/api/curriculum/semesters', $this->semester)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $semester = Semester::where('name', $this->semester['name'])
      ->where('name', $this->semester['name'])->first();
    $this->assertNotNull($semester);
  }


  /**
   * PATCH /api/curriculum/semesters/{id}
   * @group curriculum
   * @group semester
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_semester()
  {
    $semester = Semester::factory()->create();
    $semesterUpdate = Semester::factory()->make()->toArray();
    $res = $this->patchJson('/api/curriculum/semesters/' . $semester->id, $semesterUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/curriculum/semesters/{id}
   * @group curriculum
   * @group semester
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_semester()
  {
    $semester = Semester::factory()->create();
    $semesterUpdate = Semester::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/semesters/' . $semester->id, $semesterUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/curriculum/semesters/{id}
   * @group curriculum
   * @group semester
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_semester()
  {
    Permission::factory()->state(['name' => 'update semester'])->create();
    $this->user->givePermissionTo('update semester');

    $semester = Semester::factory()->create();
    $semesterUpdate = Semester::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/semesters/' . $semester->id, $semesterUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/curriculum/semesters/{id}
   * @group curriculum
   * @group semester
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update semester'])->create();
    $this->user->givePermissionTo('update semester');
    $semester = Semester::factory()->create();
    $semesterUpdate = Semester::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/semesters/' . $semester->id, $semesterUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/curriculum/semesters/{id}
   * @group curriculum
   * @group semester
   * @test
   * @group patch-request
   * @return void
   */
  public function semester_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update semester'])->create();
    $this->user->givePermissionTo('update semester');
    $semester = Semester::factory()->create();
    $semesterUpdate = Semester::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/curriculum/semesters/' . $semester->id, $semesterUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE /api/curriculum/semesters/{id}
   * @group curriculum
   * @group semester
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_semester()
  {
    $semester = Semester::factory()->create();
    $this->deleteJson('/api/curriculum/semesters/' . $semester->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/curriculum/semesters/{id}
   * @group curriculum
   * @group semester
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_semester()
  {
    $semester = Semester::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/semesters/' . $semester->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/curriculum/semesters/{id}
   * @group curriculum
   * @group semester
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_semester()
  {
    Permission::factory()->state(['name' => 'delete semester'])->create();
    $this->user->givePermissionTo('delete semester');
    $semester = Semester::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/semesters/' . $semester->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/curriculum/semesters/{id}
   * @group curriculum
   * @group semester
   * @test
   * @group delete-request
   * @return void
   */
  public function semester_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete semester'])->create();
    $this->user->givePermissionTo('delete semester');
    $semester = Semester::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/curriculum/semesters/' . $semester->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(Semester::find($semester->id));
  }
}



