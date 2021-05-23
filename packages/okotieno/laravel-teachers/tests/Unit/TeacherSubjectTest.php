<?php


namespace Okotieno\Teachers\Tests\Unit;


use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Okotieno\Teachers\Models\Teacher;
use Tests\TestCase;

class TeacherSubjectTest extends TestCase
{
  /**
   * GET /api/teachers/{user}/subjects
   * @group teachers
   * @group teacher-subjects
   * @group get-request
   * @test
   */
  public function unauthenticated_users_cannot_retrieve_teacher_units()
  {
    $units = UnitLevel::factory()->count(2)->create();
    $teacher = Teacher::factory()->create();
    $teacher->teaches()->attach($units->pluck('id')->toArray());
    $this->getJson('api/teachers/' . $teacher->id . '/subjects')
      ->assertStatus(401);

  }

  /**
   * GET /api/teachers/{user}/subjects
   * @group teachers
   * @group teacher-subjects
   * @group get-request
   * @test
   */
  public function authenticated_users_can_retrieve_teacher_units()
  {
    $units = UnitLevel::factory()->count(2)->create();
    $teacher = Teacher::factory()->create();
    $teacher->teaches()->attach($units->pluck('id')->toArray());
    $this->actingAs($this->user, 'api')->getJson('api/teachers/' . $teacher->user->id . '/subjects')
      ->assertStatus(200);

  }

  /**
   * POST /api/teachers/{user}/subjects
   * @group teachers
   * @group teacher-subjects
   * @group get-request
   * @test
   */
  public function unauthenticated_users_cannot_create_teacher_units()
  {
    $units = UnitLevel::factory()->count(2)->make();
    $teacher = Teacher::factory()->create();
    $this
      ->postJson('api/teachers/' . $teacher->user->id . '/subjects', $units->pluck('id')->toArray())
      ->assertStatus(401);
  }

  /**
   * POST /api/teachers/{user}/subjects
   * @group teachers
   * @group teacher-subjects
   * @group get-request
   * @test
   */
  public function error_422_if_units_is_not_provided_while_creating_teacher_units()
  {
    Permission::factory()->state(['name' => 'assign unit to teacher'])->create();
    $this->user->givePermissionTo('assign unit to teacher');
    $teacher = Teacher::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/teachers/' . $teacher->user->id . '/subjects', [])
      ->assertStatus(422);
  }

  /**
   * POST /api/teachers/{user}/subjects
   * @group teachers
   * @group teacher-subjects
   * @group get-request
   * @test
   */
  public function authenticated_users_with_permission_can_create_teacher_units()
  {
    Permission::factory()->state(['name' => 'assign unit to teacher'])->create();
    $this->user->givePermissionTo('assign unit to teacher');
    $units = UnitLevel::factory()->count(2)->create();
    $teacher = Teacher::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/teachers/' . $teacher->user->id . '/subjects', ['units' => $units->pluck('id')->toArray()])
      ->assertStatus(201);
  }

  /**
   * POST /api/teachers/{user}/subjects
   * @group teachers
   * @group teacher-subjects
   * @group get-request
   * @test
   */
  public function authenticated_users_without_permission_cannot_create_teacher_units()
  {
    $units = UnitLevel::factory()->count(2)->create();
    $teacher = Teacher::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/teachers/' . $teacher->user->id . '/subjects', ['units' => $units->pluck('id')->toArray()])
      ->assertStatus(403);
  }

}
