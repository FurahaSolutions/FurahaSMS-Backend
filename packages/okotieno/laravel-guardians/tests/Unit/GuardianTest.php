<?php


namespace Okotieno\Guardians\Tests\Unit;


use App\Models\User;
use Okotieno\Guardians\Models\Guardian;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\Students\Models\Student;
use Tests\TestCase;

class GuardianTest extends TestCase
{
  /**
   * GET /api/guardians/:guardian
   * @test
   * @group guardians
   * @get-request
   */
  public function unauthenticated_users_cannot_access_guardian_details()
  {
    $guardian = Guardian::factory()->create();
    $this->getJson('api/guardians/' . $guardian->user->id)
      ->assertStatus(401);
  }

  /**
   * GET /api/guardians/:guardian
   * @test
   * @group guardians-1
   * @get-request
   */
  public function authenticated_users_can_access_guardian_details()
  {
    $guardian = Guardian::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('api/guardians/' . $guardian->user->id)->assertStatus(200)
      ->assertJsonStructure(['id', 'firstName', 'lastName', 'genderName', 'religionName']);
  }


  /**
   * GET /api/guardians/:guardian
   * @test
   * @group guardians
   * @get-request
   */
  public function authenticated_users_can_access_guardian_details_with_students()
  {
    $guardian = Guardian::factory()->create();
    $students = Student::factory()->count(2)->create();
    $guardian->students()->attach($students->pluck('id'), ['relationship' => 'Parent']);
    $this->actingAs($this->user, 'api')
      ->getJson('api/guardians/' . $guardian->user->id . '?with-students=1')->assertStatus(200)
      ->assertJsonStructure(['id', 'firstName', 'lastName', 'genderName', 'religionName']);
  }

  /**
   * GET /api/students/:student/guardians
   * @test
   * @group guardians
   * @get-request
   */
  public function authenticated_users_can_retrieve_guardians_details_for_student()
  {
    $guardians = Guardian::factory()->count(2)->create();
    $student = Student::factory()->create();
    $student->guardians()->attach($guardians->pluck('id'), ['relationship' => 'Parent']);
    $this->actingAs($this->user, 'api')
      ->getJson('api/students/' . $student->user->id . '/guardians')->assertStatus(200)
      ->assertJsonStructure([['id', 'first_name', 'last_name', 'gender_name', 'religion_name']]);
  }

  /**
   * GET /api/students/:student/guardians
   * @test
   * @group guardians
   * @get-request
   */
  public function unauthenticated_users_cannot_create_guardians()
  {
    $guardian = array_merge(User::factory()->make()->toArray(), ['relationship' => 'Parent']);
    $student = Student::factory()->create();
    $this->postJson('api/students/' . $student->user->id . '/guardians', $guardian)->assertStatus(401);
  }

  /**
   * GET /api/students/:student/guardians
   * @test
   * @group guardians
   * @get-request
   */
  public function authenticated_users_without_permission_cannot_create_guardians()
  {
    $guardian = array_merge(User::factory()->make()->toArray(), ['relationship' => 'Parent']);
    $student = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/students/' . $student->user->id . '/guardians', $guardian)
      ->assertStatus(403);
  }

  /**
   * POST /api/students/:student/guardians
   * @test
   * @group guardians
   * @group get-request
   */
  public function authenticated_users_with_permission_can_create_guardians()
  {
    Permission::factory()->state(['name' => 'create guardian'])->create();
    $this->user->givePermissionTo('create guardian');
    $guardian = array_merge(User::factory()->make()->toArray(), ['relationship' => 'Parent']);
    $student = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/students/' . $student->user->id . '/guardians', $guardian)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'first_name', 'last_name']]);
  }

  /**
   * GET /api/students/:student/guardians
   * @test
   * @group guardians
   * @group get-request
   */
  public function users_cannot_create_guardian_for_non_student()
  {
    Permission::factory()->state(['name' => 'create guardian'])->create();
    $this->user->givePermissionTo('create guardian');
    $guardian = array_merge(User::factory()->make()->toArray(), ['relationship' => 'Parent']);
    $user = User::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/students/' . $user->id . '/guardians', $guardian)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/guardians/:guardian
   * @test
   * @group guardians-1
   * @group get-request
   */
  public function authenticated_users_with_permission_can_update_guardians()
  {
    Permission::factory()->state(['name' => 'update guardian'])->create();
    $this->user->givePermissionTo('update guardian');
    $guardian = Guardian::factory()->create();
    $guardianUpdate = User::factory()->make()->toArray();
    $student = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->patchJson('api/guardians/'.$guardian->user->id, $guardianUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'first_name', 'last_name']]);
  }
}
