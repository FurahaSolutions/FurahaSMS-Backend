<?php

namespace Okotieno\AcademicYear\Tests\Unit;

use App\Models\User;
use Carbon\Carbon;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\AcademicYearUnitAllocation;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolAccounts\Models\TuitionFeeFinancialPlan;
use Tests\TestCase;


class AcademicYearTest extends TestCase
{
  private $academicYear;

  protected function setUp(): void
  {
    parent::setUp();

    $this->academicYear = AcademicYear::factory()->create();
  }

  /**
   * GET /academic-year
   * @group academic-year
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_academic_year()
  {
    $this->getJson('/api/academic-years')->assertStatus(401);
  }

  /**
   * GET /academic-year
   * @group academic-year
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_active_academic_year()
  {
    $time = new Carbon();
    AcademicYear::factory()->state(['archived_at' => $time])->create();
    AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('/api/academic-years?archived=0')
      ->assertStatus(200)
      ->assertJsonMissing(['archived_at' => $time])
      ->assertJsonFragment(['archived_at' => null]);
  }

  /**
   * GET /academic-year
   * @group academic-year
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_archived_academic_year()
  {
    $time = (new Carbon())->format('Y-m-d h:m:s');
    AcademicYear::factory()->state(['archived_at' => $time])->create();
    AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('/api/academic-years?archived=1')
      ->assertStatus(200)
      ->assertJsonFragment(['archived_at' => $time])
      ->assertJsonMissing(['archived_at' => null]);
  }

  /**
   * GET /academic-year
   * @group academic-year
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_retrieve_deleted_academic_year()
  {
    $time = (new Carbon())->format('Y-m-d h:m:s');
    AcademicYear::factory()->state(['deleted_at' => $time])->create();
    AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('/api/academic-years?deleted=1')
      ->assertStatus(403);
  }

  /**
   * GET /academic-year
   * @group academic-year
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_retrieve_deleted_academic_year()
  {
    $time = (new Carbon())->format('Y-m-d h:m:s');
    AcademicYear::factory()->state(['deleted_at' => $time])->create();
    AcademicYear::factory()->create();
    Permission::factory()->state(['name' => 'view deleted academic year'])->create();
    $this->user->givePermissionTo('view deleted academic year');
    $this->actingAs($this->user, 'api')
      ->getJson('/api/academic-years?deleted=1')
      ->assertStatus(200)
      ->assertSeeText(substr($time, 0, 10))
      ->assertJsonMissing(['deleted_at' => null]);
  }


  /**
   * GET /academic-years/{id}?semesters=1
   * @group academic-year
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_academic_year_with_semesters()
  {
    $academicYear = AcademicYear::factory()->create();
    TuitionFeeFinancialPlan::factory()->state([
      'academic_year_id' => $academicYear->id
    ])->create();
    $this->actingAs($this->user, 'api')
      ->getJson('/api/academic-years/' . $academicYear->id . '?semesters=1')
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name', 'semesters' => [['id', 'name']]]);

  }

  /**
   * GET /academic-years/{id}?semesters=1
   * @group academic-year
   * @group academic-year-sem
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_academic_year_with_class_levels()
  {
    $academicYear = AcademicYear::factory()->create();
    AcademicYearUnitAllocation::factory()->state([
      'academic_year_id' => $academicYear->id
    ])->create();
    $this->actingAs($this->user, 'api')
      ->getJson('/api/academic-years/' . $academicYear->id . '?class_levels=1')
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name', 'archived', 'class_level_allocations' => [['id', 'name']]]);

  }

  /**
   * POST /academic-year
   * @group academic-year
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_academic_year()
  {
    $this->postJson('/api/academic-years', ['name' => $this->faker->year])
      ->assertStatus(401);
  }

  /**
   * POST /academic-year
   * @group academic-year
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_academic_year()
  {
    $user = User::factory()->create();
    $this->actingAs($user, 'api')->postJson('/api/academic-years', ['name' => $this->faker->year])
      ->assertStatus(403);
  }

  /**
   * POST /academic-year
   * @group academic-year
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_academic_year()
  {
    $academicYear = AcademicYear::factory()->make()->toArray();
    Permission::factory()->state(['name' => 'create academic year'])->create();
    $user = User::factory()->create();
    $user->givePermissionTo('create academic year');
    $this->actingAs($user, 'api')
      ->postJson('/api/academic-years', $academicYear)
      ->assertStatus(201);
  }

  /**
   * POST /academic-year
   * @group academic-year
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    Permission::factory()->state(['name' => 'create academic year'])->create();
    $user = User::factory()->create();
    $user->givePermissionTo('create academic year');
    $this->actingAs($user, 'api')->postJson('/api/academic-years', [])
      ->assertStatus(422);
  }

  /**
   * POST /academic-year
   * @group academic-year
   * @test
   * @return void
   */
  public function should_throw_error_if_start_date_is_gt_end_date()
  {
    Permission::factory()->state(['name' => 'create academic year'])->create();
    $user = User::factory()->create();
    $user->givePermissionTo('create academic year');
    $this->actingAs($user, 'api')->postJson('/api/academic-years', [
      'name' => $this->faker->year,
      'start_date' => '2020-01-01',
      'end_date' => '2019-01-01'
    ])
      ->assertStatus(422);
  }

  /**
   * POST /academic-year
   * @group academic-year
   * @test
   * @return void
   */
  public function should_throw_error_if_date_format_is_invalid()
  {
    Permission::factory()->state(['name' => 'create academic year'])->create();
    $user = User::factory()->create();
    $user->givePermissionTo('create academic year');
    $this->actingAs($user, 'api')->postJson('/api/academic-years', [
      'name' => $this->faker->year,
      'start_date' => '01-01-2019',
      'end_date' => '2019-01-01'
    ])->assertStatus(422);
    $this->actingAs($user, 'api')->postJson('/api/academic-years', [
      'name' => $this->faker->year,
      'end_date' => '01-01-2019',
      'start_date' => '2019-01-01'
    ])->assertStatus(422);
  }

  /**
   * PATCH /academic-years/{id}
   * @group academic-year
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_academic_year()
  {
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->make()->toArray();
    $res = $this->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /academic-years/{id}
   * @group academic-year
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_academic_year()
  {
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /academic-years/{id}
   * @group academic-year
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_academic_year()
  {
    Permission::factory()->state(['name' => 'update academic year'])->create();
    $this->user->givePermissionTo('update academic year');
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->make()->toArray();

    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate)
      ->assertStatus(200);
  }

  /**
   * PATCH /academic-years/{id}
   * @group academic-year
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update academic year'])->create();
    $this->user->givePermissionTo('update academic year');
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->state(['name' => ''])->make()->toArray();

    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /academic-years/{id}
   * @group academic-year
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_start_date_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update academic year'])->create();
    $this->user->givePermissionTo('update academic year');
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->state(['start_date' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /academic-years/{id}
   * @group academic-year
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_end_date_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update academic year'])->create();
    $this->user->givePermissionTo('update academic year');
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->state(['end_date' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /academic-years/{id}
   * @group academic-year
   * @group patch-request
   * @test
   * @return void
   */
  public function should_throw_error_if_date_format_is_invalid_on_update()
  {
    Permission::factory()->state(['name' => 'update academic year'])->create();
    $this->user->givePermissionTo('update academic year');
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()
      ->state(['start_date' => '01-01-2017'])
      ->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /academic-years/{id}
   * @group academic-year-current
   * @test
   * @group patch-request
   * @return void
   */
  public function academic_year_should_be_updated_after_successful_call()
  {
    $academicYearUpdate = AcademicYear::factory()->make()->toArray();
    $academicYear = AcademicYear::factory()->create();

    Permission::factory()->state(['name' => 'update academic year'])->create();
    $this->user->givePermissionTo('update academic year');

    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate)
      ->assertStatus(200)
      ->assertJsonStructure([
        'saved',
        'message',
        'data' => ['id', 'name', 'start_date', 'end_date']
      ]);
  }

  /**
   * DELETE/academic-years/{id}
   * @group academic-year
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_academicYear()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->deleteJson('/api/academic-years/' . $academicYear->id)
      ->assertStatus(401);

  }

  /**
   * DELETE/academic-years/{id}
   * @group academic-year
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_academicYear()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/academic-years/' . $academicYear->id)
      ->assertStatus(403);
  }

  /**
   * DELETE/academic-years/{id}
   * @group academic-year
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_academicYear()
  {
    Permission::factory()->state(['name' => 'delete academic year'])->create();
    $this->user->givePermissionTo('delete academic year');

    $academicYear = AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/academic-years/' . $academicYear->id)
      ->assertStatus(200);
  }

  /**
   * DELETE/academic-years/{id}
   * @group academic-year
   * @test
   * @group delete-request
   * @return void
   */
  public function academicYear_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete academic year'])->create();
    $this->user->givePermissionTo('delete academic year');
    $academicYear = AcademicYear::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/academic-years/' . $academicYear->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(AcademicYear::find($academicYear->id));
  }

  /**
   * POST /academic-years/{id}/restore
   * @group academic-year
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_restore_academicYear()
  {
    $academicYear = AcademicYear::factory()->deleted()->create();
    $this->postJson('/api/academic-years/' . $academicYear->id . '/restore')
      ->assertStatus(401);

  }

  /**
   * DELETE/academic-years/{id}
   * @group academic-year
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_restore_academicYear()
  {
    $academicYear = AcademicYear::factory()->deleted()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/academic-years/' . $academicYear->id . '/restore')
      ->assertStatus(403);
  }

  /**
   * DELETE/academic-years/{id}
   * @group academic-year
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_restore_academicYear()
  {
    Permission::factory()->state(['name' => 'restore academic year'])->create();
    $this->user->givePermissionTo('restore academic year');

    $academicYear = AcademicYear::factory()->deleted()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/academic-years/' . $academicYear->id . '/restore')
      ->assertStatus(200);
  }

  /**
   * DELETE/academic-years/{id}
   * @group academic-year
   * @test
   * @group post-request
   * @return void
   */
  public function academicYear_should_be_restored_after_successful_call()
  {
    Permission::factory()->state(['name' => 'restore academic year'])->create();
    $this->user->givePermissionTo('restore academic year');
    $academicYear = AcademicYear::factory()->deleted()->create();
    $res = $this->actingAs($this->user, 'api')
      ->postJson('/api/academic-years/' . $academicYear->id. '/restore');
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNotNull(AcademicYear::find($academicYear->id));
  }

  /**
   * POST/academic-years/{id}
   * @group academic-year
   * @test
   * @group post-request
   * @return void
   */
  public function academicYear_should_be_deleted_after_successful_call_academic_year()
  {
    Permission::factory()->state(['name' => 'delete academic year'])->create();
    $this->user->givePermissionTo('delete academic year');
    $academicYear = AcademicYear::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/academic-years/' . $academicYear->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(AcademicYear::find($academicYear->id));
  }
}
