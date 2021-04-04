<?php

namespace Okotieno\AcademicYear\Tests\Unit;

use App\Models\User;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;


class AcademicYearTest extends TestCase
{
  private $academicYear;

  protected function setUp(): void
  {
    parent::setUp();

    AcademicYear::factory()->create();
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
    Permission::factory()->state(['name' => 'create academic year'])->create();
    $user = User::factory()->create();
    $user->givePermissionTo('create academic year');
    $response = $this->actingAs($user, 'api')->postJson('/api/academic-years', [
      'name' => $this->faker->year,
      'start_date' => '2020-01-30',
      'end_date' => '2021-01-30',
    ]);

    $response->assertStatus(201);
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

    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->make()->toArray();
    $this->user->permissions()->create(['name' => 'update academic year']);
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate);
    $response->assertStatus(200);
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
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->state(['name' => ''])->make()->toArray();
    $this->user->permissions()->create(['name' => 'update academic year']);
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
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->state(['start_date' => ''])->make()->toArray();
    $this->user->permissions()->create(['name' => 'update academic year']);
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
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->state(['end_date' => ''])->make()->toArray();
    $this->user->permissions()->create(['name' => 'update academic year']);
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
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()
      ->state(['start_date' => '01-01-2017'])
      ->make()->toArray();
    $this->user->permissions()->create(['name' => 'update academic year']);
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/' . $academicYear->id, $academicYearUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /academic-years/{id}
   * @group academic-year
   * @test
   * @group patch-request
   * @return void
   */
  public function academic_year_should_be_updated_after_successful_call()
  {
    $academicYear = AcademicYear::factory()->create();
    $academicYearUpdate = AcademicYear::factory()->make()->toArray();
    $this->user->permissions()->create(['name' => 'update academic year']);
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
    $academicYear = AcademicYear::factory()->create();
    $this->user->permissions()->create(['name' => 'delete academic year']);
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
    $academicYear = AcademicYear::factory()->create();
    $this->user->permissions()->create(['name' => 'delete academic year']);
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/academic-years/' . $academicYear->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(AcademicYear::find($academicYear->id));
  }

  /**
   * POST/academic-years/{id}
   * @group academic-year
   * @test
   * @group post-request
   * @return void
   */
  public function after_successfull_call_academic_year()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->user->permissions()->create(['name' => 'delete academic year']);
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/academic-years/' . $academicYear->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(AcademicYear::find($academicYear->id));
  }


}
