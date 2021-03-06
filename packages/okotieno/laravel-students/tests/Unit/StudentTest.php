<?php


namespace Okotieno\Students\Tests\Unit;


use App\Models\User;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\PermissionsAndRoles\Models\Role;
use Okotieno\Students\Models\Student;
use Tests\TestCase;

class StudentTest extends TestCase
{
  /**
   * GET /api/students?limit=:limit
   * @group students
   * @group get-request
   * @test
   */
  public function unauthenticated_users_cannot_retrieve_recently_created_students()
  {
    Student::factory()->count(7)->create();
    $this->getJson('api/students?limit=5')
      ->assertStatus(401);

  }

  /**
   * GET /api/students?limit=:limit
   * @group students
   * @group get-request
   * @test
   */
  public function authenticated_users_can_retrieve_recently_created_students()
  {
    Student::factory()->count(7)->create();
    $response = $this->actingAs($this->user, 'api')->getJson('api/students?limit=5');
    $response->assertStatus(200);
    $response->assertJsonStructure([['id', 'first_name', 'last_name']]);
    $lastRecord = Student::orderBy('id', 'desc')->take(1)->first();
    $response->assertJsonFragment(['id' => $lastRecord->user->id]);
    $response->assertJsonFragment(['middle_name' => $lastRecord->user->middle_name]);

  }

  /**
   * GET /api/students?id=:id
   * @group students
   * @group get-request
   * @test
   */
  public function authenticated_users_can_query_student_by_id()
  {
    $student = Student::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/students/' . $student->user->id);
    $response->assertStatus(200);
    $response->assertJsonStructure(['id', 'first_name', 'last_name']);
    $response->assertJsonFragment(['id' => $student->user->id]);
    $response->assertJsonFragment(['middle_name' => $student->user->middle_name]);
  }

  /**
   * GET /api/students?id=:id
   * @group students
   * @group get-request
   * @test
   */
  public function error_422_if_id_is_not_for_a_student_user()
  {
    $user = User::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/students/' . $user->id);
    $response->assertStatus(422);
  }

  /**
   * GET /api/students?school_id_number=:school_id
   * @group students
   * @group get-request
   * @test
   */
  public function authenticated_users_can_query_student_by_school_id_number()
  {
    $schoolIdNumber = Student::generateIdNumber();
    $student = Student::factory()->state(['student_school_id_number' => $schoolIdNumber])->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/students?school_id=' . $schoolIdNumber);
    $response->assertStatus(200);
    $response->assertJsonStructure(['id', 'first_name', 'last_name']);
    $response->assertJsonFragment(['id' => $student->user->id]);
    $response->assertJsonFragment(['middle_name' => $student->user->middle_name]);

  }

  /**
   * GET /api/students?school_id_number=:school_id_number
   * @group students
   * @group get-request
   * @test
   */
  public function returns_empty_if_no_user_with_id_number_found()
  {
    $schoolIdNumber = 'some_non_existent_id';
    Student::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/students?school_id=' . $schoolIdNumber);
    $response->assertNoContent();

  }

  /**
   * GET /api/students?id=:id
   * @group students
   * @group get-request
   * @test
   */
  public function authenticated_users_can_query_student_by_text()
  {
    $student = Student::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/students?q=' . $student->user->first_name);
    $response->assertStatus(200);
    $response->assertJsonStructure([['id', 'first_name', 'last_name']]);
    $response->assertJsonFragment(['id' => $student->user->id]);
    $response->assertJsonFragment(['middle_name' => $student->user->middle_name]);

  }

  /**
   * POST /api/students
   * @group students
   * @group post-request
   * @test
   */
  public function unauthenticated_users_cannot_create_student()
  {
    $student = User::factory()->make();
    $student['student_school_id_number'] = Student::factory()->make()->student_school_id_number;
    $response = $this->postJson('api/students', $student->toArray());
    $response->assertStatus(401);

  }

  /**
   * POST /api/students
   * @group students
   * @group post-request
   * @test
   */
  public function authenticated_users_with_permission_can_create_student()
  {
    Role::factory()->state(['name' => 'student'])->create();
    Permission::factory()->state(['name' => 'create student'])->create();
    $this->user->givePermissionTo('create student');
    $student = User::factory()->make();
    $student['student_school_id_number'] = Student::factory()->make()->student_school_id_number;
    $response = $this->actingAs($this->user, 'api')
      ->postJson('api/students', $student->toArray());
    $response->assertStatus(201);
    $response->assertJsonStructure(['saved', 'message', 'data' => ['id', 'first_name', 'last_name']]);
    $response->assertJsonFragment(['middle_name' => $student->middle_name]);

  }

  /**
   * POST /api/students
   * @group students
   * @group post-request
   * @test
   */
  public function authenticated_users_with_permission_can_create_student_with_autogenerated_school_id()
  {
    Role::factory()->state(['name' => 'student'])->create();
    Permission::factory()->state(['name' => 'create student'])->create();
    $this->user->givePermissionTo('create student');
    $student = User::factory()->make();
    $response = $this->actingAs($this->user, 'api')
      ->postJson('api/students', $student->toArray());
    $response->assertStatus(201);
    $response->assertJsonStructure(['saved', 'message', 'data' => ['id', 'first_name', 'last_name']]);
    $response->assertJsonFragment(['middle_name' => $student->middle_name]);

  }

  /**
   * POST /api/students
   * @group students
   * @group post-request
   * @test
   */
  public function authenticated_users_without_permission_cannot_create_student()
  {
    $student = User::factory()->make();
    $student['student_school_id_number'] = Student::factory()->make()->student_school_id_number;
    $response = $this->actingAs($this->user, 'api')
      ->postJson('api/students', $student->toArray());
    $response->assertStatus(403);
  }

  /**
   * PATCH /api/students/:user
   * @group students
   * @group patch-request
   * @test
   */
  public function unauthenticated_users_cannot_update_student()
  {
    $student = Student::factory()->create();
    $studentUpdate = User::factory()->make();
    $studentUpdate['student_school_id_number'] = Student::factory()->make()->student_school_id_number;
    $response = $this->patchJson('api/students/' . $student->user->id, $studentUpdate->toArray());
    $response->assertStatus(401);

  }

  /**
   * PATCH /api/students/:user
   * @group students
   * @group patch-request
   * @test
   */
  public function authenticated_users_with_permission_can_update_student()
  {
    $student = Student::factory()->create();
    $studentUpdate = User::factory()->make();
    $studentUpdate['student_school_id_number'] = Student::factory()->make()->student_school_id_number;
    Permission::factory()->state(['name' => 'update student'])->create();
    $this->user->givePermissionTo('update student');

    $response = $this->actingAs($this->user, 'api')
      ->patchJson('api/students/' . $student->user->id, $studentUpdate->toArray());
    $response->assertStatus(200);
    $response->assertJsonStructure(['saved', 'message', 'data' => ['id', 'first_name', 'last_name']]);
    $response->assertJsonFragment(['middle_name' => $studentUpdate->middle_name]);

  }

  /**
   * PATCH /api/students/:user
   * @group students
   * @group patch-request
   * @test
   */
  public function authenticated_users_without_permission_cannot_update_student()
  {
    $student = Student::factory()->create();
    $studentUpdate = User::factory()->make();
    $studentUpdate['student_school_id_number'] = Student::factory()->make()->student_school_id_number;
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('api/students/' . $student->user->id, $studentUpdate->toArray());
    $response->assertStatus(403);
  }

}
