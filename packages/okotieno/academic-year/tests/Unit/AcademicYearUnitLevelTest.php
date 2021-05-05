<?php


namespace Okotieno\AcademicYear\Tests\Unit;


use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;

class AcademicYearUnitLevelTest extends TestCase
{
  /**
   * GET api/academic-year/:academic-year/unit-levels
   * @group academic-year-unit-levels
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_academic_year_unit_levels()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->getJson('/api/academic-years/' . $academicYear->id . '/unit-levels', [])
      ->assertStatus(401);
  }

  /**
   * GET api/academic-year/:academic-year/unit-levels
   * @group academic-year-unit-levels
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_academic_year_unit_levels()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/academic-years/' . $academicYear->id . '/unit-levels', [])
      ->assertStatus(200);
  }

  /**
   * POST api/academic-year/:academic-year/unit-levels
   * @group academic-year-unit-levels
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_allocate_student_unit_levels()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->postJson('/api/academic-years/' . $academicYear->id . '/unit-levels', [])
      ->assertStatus(401);
  }

  /**
   * POST api/academic-year/:academic-year/unit-levels
   * @group academic-year-unit-levels
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_allocate_student_unit_levels()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/academic-years/' . $academicYear->id . '/unit-levels', [])
      ->assertStatus(403);
  }

  /**
   * POST api/academic-year/:academic-year/unit-levels
   * @group academic-year-unit-levels
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_allocate_student_unit_levels()
  {
    Permission::factory()->state(['name' => 'create academic year unit levels'])->create();
    $this->user->givePermissionTo('create academic year unit levels');
    $academicYear = AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/academic-years/' . $academicYear->id . '/unit-levels', [])
      ->assertStatus(201);
  }

}
