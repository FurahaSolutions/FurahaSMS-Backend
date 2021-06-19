<?php


namespace Okotieno\Teachers\Tests\Unit;


use App\Models\User;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\PermissionsAndRoles\Models\Role;
use Okotieno\Teachers\Models\Teacher;
use Tests\TestCase;

class TeacherTest extends TestCase
{
  /**
   * GET /api/teachers?limit=:limit
   * @group teachers
   * @group get-request
   * @test
   */
  public function unauthenticated_users_cannot_retrieve_recently_created_teachers()
  {
    Teacher::factory()->count(7)->create();
    $this->getJson('api/teachers?limit=5')
      ->assertStatus(401);

  }

  /**
   * GET /api/teachers?limit=:limit
   * @group teachers
   * @group get-request
   * @test
   */
  public function authenticated_users_can_retrieve_recently_created_teachers()
  {
    Teacher::factory()->count(7)->create();
    $response = $this->actingAs($this->user, 'api')->getJson('api/teachers?limit=5');
    $response->assertStatus(200);
    $response->assertJsonStructure([['id', 'first_name', 'last_name']]);
    $lastRecord = Teacher::orderBy('id', 'desc')->take(1)->first();
    $response->assertJsonFragment(['id' => $lastRecord->user->id]);
    $response->assertJsonFragment(['middle_name' => $lastRecord->user->middle_name]);

  }

  /**
   * GET /api/teachers?id=:id
   * @group teachers
   * @group get-request
   * @test
   */
  public function authenticated_users_can_query_teacher_by_id()
  {
    $teacher = Teacher::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/teachers/'.$teacher->user->id);
    $response->assertStatus(200);
    $response->assertJsonStructure(['id', 'first_name', 'last_name']);
    $response->assertJsonFragment(['id' => $teacher->user->id]);
    $response->assertJsonFragment(['middle_name' => $teacher->user->middle_name]);
  }

  /**
   * GET /api/teachers/:id
   * @group teachers
   * @group get-request
   * @test
   */
  public function error_422_if_user_not_teacher()
  {
    $user = User::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/teachers/'.$user->id);
    $response->assertStatus(422);
  }

  /**
   * GET /api/teachers?id=:id
   * @group teachers
   * @group get-request
   * @test
   */
  public function authenticated_users_can_query_teacher_by_text()
  {
    $teacher = Teacher::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/teachers?q='.$teacher->user->first_name);
    $response->assertStatus(200);
    $response->assertJsonStructure([['id', 'first_name', 'last_name']]);
    $response->assertJsonFragment(['id' => $teacher->user->id]);
    $response->assertJsonFragment(['middle_name' => $teacher->user->middle_name]);

  }

  /**
   * POST /api/teachers
   * @group teachers
   * @group post-request
   * @test
   */
  public function unauthenticated_users_cannot_create_teacher()
  {
    $teacher = User::factory()->make();
    $teacher['teacher_school_id_number'] = Teacher::factory()->make()->teacher_school_id_number;
    $response = $this->postJson('api/teachers', $teacher->toArray());
    $response->assertStatus(401);

  }

  /**
   * POST /api/teachers
   * @group teachers-1
   * @group post-request
   * @test
   */
  public function authenticated_users_with_permission_can_create_teacher()
  {
    Role::factory()->state(['name' => 'teacher'])->create();
    Permission::factory()->state(['name' => 'create teacher'])->create();
    $this->user->givePermissionTo('create teacher');
    $teacher = User::factory()->make();
    $teacher['teacher_school_id_number'] = Teacher::factory()->make()->teacher_school_id_number;
    $response = $this->actingAs($this->user, 'api')
      ->postJson('api/teachers', $teacher->toArray());
    $response->assertStatus(201);
    $response->assertJsonStructure(['saved', 'message', 'data'=> ['id', 'first_name', 'last_name']]);
    $response->assertJsonFragment(['middle_name' => $teacher->middle_name]);

  }

  /**
   * POST /api/teachers
   * @group teachers
   * @group post-request
   * @test
   */
  public function authenticated_users_without_permission_cannot_create_teacher()
  {
    $teacher = User::factory()->make();
    $teacher['teacher_school_id_number'] = Teacher::factory()->make()->teacher_school_id_number;
    $response = $this->actingAs($this->user, 'api')
      ->postJson('api/teachers', $teacher->toArray());
    $response->assertStatus(403);
  }

  /**
   * PATCH /api/teachers/:user
   * @group teachers
   * @group patch-request
   * @test
   */
  public function unauthenticated_users_cannot_update_teacher()
  {
    $teacher = Teacher::factory()->create();
    $teacherUpdate = User::factory()->make();
    $teacherUpdate['teacher_school_id_number'] = Teacher::factory()->make()->teacher_school_id_number;
    $response = $this->patchJson('api/teachers/'.$teacher->user->id, $teacherUpdate->toArray());
    $response->assertStatus(401);

  }

  /**
   * PATCH /api/teachers/:user
   * @group teachers
   * @group patch-request
   * @test
   */
  public function authenticated_users_with_permission_can_update_teacher()
  {
    $teacher = Teacher::factory()->create();
    $teacherUpdate = User::factory()->make();
    $teacherUpdate['teacher_school_id_number'] = Teacher::factory()->make()->teacher_school_id_number;
    Permission::factory()->state(['name' => 'update teacher'])->create();
    $this->user->givePermissionTo('update teacher');
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('api/teachers/'.$teacher->user->id, $teacherUpdate->toArray());
    $response->assertStatus(200);
    $response->assertJsonStructure(['saved', 'message', 'data'=> ['id', 'first_name', 'last_name']]);
    $response->assertJsonFragment(['middle_name' => $teacherUpdate->middle_name]);

  }

  /**
   * PATCH /api/teachers/:user
   * @group teachers
   * @group patch-request
   * @test
   */
  public function authenticated_users_without_permission_cannot_update_teacher()
  {
    $teacher = Teacher::factory()->create();
    $teacherUpdate = User::factory()->make();
    $teacherUpdate['teacher_school_id_number'] = Teacher::factory()->make()->teacher_school_id_number;
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('api/teachers/'.$teacher->user->id, $teacherUpdate->toArray());
    $response->assertStatus(403);
  }

}
