<?php

namespace Okotieno\AcademicYear\Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;


class AcademicYearTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

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
}
