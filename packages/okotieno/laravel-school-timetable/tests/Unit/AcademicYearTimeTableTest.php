<?php


namespace Okotieno\TimeTable\Tests\Unit;


use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\TimeTable\Models\TimeTable;
use Tests\TestCase;

class AcademicYearTimeTableTest extends TestCase
{
  /**
   * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
   */
  private $academicYear;
  private string $url;
  /**
   * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
   */
  private $timeTable;

  protected function setUp(): void
  {
    parent::setUp();
    $this->academicYear = AcademicYear::factory()->create();
    $this->timeTable = TimeTable::factory()
      ->state(['academic_year_id' => $this->academicYear->id])
      ->create();
    $this->url = 'api/academic-year/' . $this->academicYear->id . '/time-tables';
  }

  /**
   * GET /api/academic-year/:academic-year/time-tables
   * @group get-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function unauthenticated_users_cannot_retrieve_academic_year_time_tables()
  {
    $this->getJson($this->url)
      ->assertStatus(401);
  }

  /**
   * GET /api/academic-year/:academic-year/time-tables
   * @group get-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function authenticated_users_can_retrieve_academic_year_time_tables()
  {
    $this->actingAs($this->user, 'api')->getJson($this->url)
      ->assertStatus(200);
  }

  /**
   * GET /api/academic-year/:academic-year/time-tables
   * @group get-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function unauthenticated_users_cannot_retrieve_academic_year_time_table()
  {
    $timeTable = TimeTable::factory()->state(['academic_year_id' => $this->academicYear->id])->create();
    $this->getJson($this->url . '/' . $timeTable)
      ->assertStatus(401);
  }

  /**
   * GET /api/academic-year/:academic-year/time-tables
   * @group get-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function authenticated_users_can_retrieve_academic_year_time_table()
  {
    $timeTable = TimeTable::factory()->state(['academic_year_id' => $this->academicYear->id])->create();
    $this->actingAs($this->user, 'api')
      ->getJson($this->url . '/' . $timeTable->id)
      ->assertStatus(200);
  }

  /**
   * POST /api/academic-year/:academic-year/time-tables
   * @group post-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function unauthenticated_users_cannot_create_academic_year_time_table()
  {
    $this->postJson($this->url, [])
      ->assertStatus(401);
  }

  /**
   * POST /api/academic-year/:academic-year/time-tables
   * @group post-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function authenticated_users_without_permission_cannot_create_academic_year_time_table()
  {
    $this->actingAs($this->user, 'api')->postJson($this->url, [])
      ->assertStatus(403);
  }

  /**
   * POST /api/academic-year/:academic-year/time-tables
   * @group post-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function authenticated_users_with_permission_can_create_academic_year_time_table()
  {
    $timeTable = TimeTable::factory()->make()->toArray();
    $timeTable['timing'] = $timeTable['time_table_timing_template_id'];
    Permission::factory()->state(['name' => 'create academic year time table'])->create();
    $this->user->givePermissionTo('create academic year time table');
    $this->actingAs($this->user, 'api')->postJson($this->url, $timeTable)
      ->assertStatus(201);
  }

  /**
   * PATCH /api/academic-year/:academic-year/time-tables/:time-table
   * @group patch-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function unauthenticated_users_cannot_update_academic_year_time_table()
  {
    $this->patchJson($this->url . '/' . $this->timeTable->id, [])
      ->assertStatus(401);
  }

  /**
   * PATCH /api/academic-year/:academic-year/time-tables/:time-table
   * @group patch-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function authenticated_users_without_permission_cannot_update_academic_year_time_table()
  {
    $this->actingAs($this->user, 'api')
      ->patchJson($this->url . '/' . $this->timeTable->id, [])
      ->assertStatus(403);
  }

  /**
   * PATCH /api/academic-year/:academic-year/time-tables/:time-table
   * @group patch-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function authenticated_users_with_permission_can_update_academic_year_time_table()
  {
    $timeTable = TimeTable::factory()->state(['academic_year_id' => $this->academicYear->id])->create();
    $timeTableUpdate = TimeTable::factory()->state(['academic_year_id' => $this->academicYear->id])->make()->toArray();
    $timeTable['timing'] = $timeTable['time_table_timing_template_id'];
    Permission::factory()->state(['name' => 'update academic year time table'])->create();
    $this->user->givePermissionTo('update academic year time table');
    $this->actingAs($this->user, 'api')->patchJson($this->url . '/' . $timeTable->id, $timeTableUpdate)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/academic-year/:academic-year/time-tables/:time-table
   * @group delete-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function unauthenticated_users_cannot_delete_academic_year_time_table()
  {
    $this->deleteJson($this->url . '/' . $this->timeTable->id,)
      ->assertStatus(401);
  }

  /**
   * DELETE /api/academic-year/:academic-year/time-tables/:time-table
   * @group delete-request
   * @group time-table
   * @group academic-year-time-table-lessons
   * @test
   * */

  public function authenticated_users_with_permission_can_delete_academic_year_time_table()
  {
    $timeTable = TimeTable::factory()->state(['academic_year_id' => $this->academicYear->id])->create();
    $timeTable['timing'] = $timeTable['time_table_timing_template_id'];
    Permission::factory()->state(['name' => 'delete academic year time table'])->create();
    $this->user->givePermissionTo('delete academic year time table');
    $this->actingAs($this->user, 'api')->deleteJson($this->url . '/' . $timeTable->id)
      ->assertStatus(200);
  }

}
