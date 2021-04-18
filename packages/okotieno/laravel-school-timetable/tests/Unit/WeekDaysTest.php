<?php

namespace Okotieno\TimeTable\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\TimeTable\Models\WeekDay;
use Tests\TestCase;


class WeekDaysTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $weekday;


  protected function setUp(): void
  {
    parent::setUp();
    $this->weekday = WeekDay::factory()->make()->toArray();
  }

  /**
   * GET /api/time-table/week-days
   * @group timetable
   * @group weekday
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_weekdays()
  {
    $this->getJson('/api/time-table/week-days', $this->weekday)
      ->assertStatus(401);

  }

  /**
   * GET /api/time-table/week-days
   * @group timetable
   * @group weekday
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_weekdays()
  {
    WeekDay::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/time-table/week-days', $this->weekday)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/time-table/week-days/:id
   * @group timetable
   * @group weekday
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_weekday()
  {
    $weekday = WeekDay::factory()->create();
    $this->getJson('/api/time-table/week-days/' . $weekday->id, $this->weekday)
      ->assertStatus(401);

  }

  /**
   * GET /api/time-table/week-days/:id
   * @group timetable
   * @group weekday
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_weekday()
  {
    $weekday = WeekDay::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/time-table/week-days/' . $weekday->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }


  /**
   * POST /api/time-table/week-days
   * @group timetable
   * @group weekday
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_weekday()
  {
    $this->postJson('/api/time-table/week-days', $this->weekday)
      ->assertStatus(401);

  }

  /**
   * POST /api/time-table/week-days
   * @group timetable
   * @group weekday
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_weekday()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/time-table/week-days', $this->weekday)
      ->assertStatus(403);
  }

  /**
   * POST /api/time-table/week-days
   * @group timetable
   * @group weekday
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_weekday()
  {
    Permission::factory()->state(['name' => 'create weekday'])->create();
    $this->user->givePermissionTo('create weekday');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/time-table/week-days', $this->weekday)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * POST /api/time-table/week-days
   * @group timetable
   * @group weekday
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->weekday['name'] = '';
    Permission::factory()->state(['name' => 'create weekday'])->create();
    $this->user->givePermissionTo('create weekday');
    $this->actingAs($this->user, 'api')->postJson('/api/time-table/week-days', $this->weekday)
      ->assertStatus(422);
  }


  /**
   * POST /api/time-table/week-days
   * @group timetable
   * @group weekday
   * @test
   * @group post-request
   * @return void
   */
  public function weekday_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create weekday'])->create();
    $this->user->givePermissionTo('create weekday');
    $this->actingAs($this->user, 'api')->postJson('/api/time-table/week-days', $this->weekday)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $weekday = WeekDay::where('name', $this->weekday['name'])
      ->where('name', $this->weekday['name'])->first();
    $this->assertNotNull($weekday);
  }


  /**
   * PATCH /api/time-table/week-days/{id}
   * @group timetable
   * @group weekday
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_weekday()
  {
    $weekday = WeekDay::factory()->create();
    $weekdayUpdate = WeekDay::factory()->make()->toArray();
    $res = $this->patchJson('/api/time-table/week-days/' . $weekday->id, $weekdayUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/time-table/week-days/{id}
   * @group timetable
   * @group weekday-1
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_weekday()
  {
    $weekday = WeekDay::factory()->create();
    $weekdayUpdate = WeekDay::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/time-table/week-days/' . $weekday->id, $weekdayUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/time-table/week-days/{id}
   * @group timetable
   * @group weekday
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_weekday()
  {
    Permission::factory()->state(['name' => 'update weekday'])->create();
    $this->user->givePermissionTo('update weekday');

    $weekday = WeekDay::factory()->create();
    $weekdayUpdate = WeekDay::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/time-table/week-days/' . $weekday->id, $weekdayUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/time-table/week-days/{id}
   * @group timetable
   * @group weekday
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update weekday'])->create();
    $this->user->givePermissionTo('update weekday');
    $weekday = WeekDay::factory()->create();
    $weekdayUpdate = WeekDay::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/time-table/week-days/' . $weekday->id, $weekdayUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/time-table/week-days/{id}
   * @group timetable
   * @group weekday
   * @test
   * @group patch-request
   * @return void
   */
  public function weekday_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update weekday'])->create();
    $this->user->givePermissionTo('update weekday');
    $weekday = WeekDay::factory()->create();
    $weekdayUpdate = WeekDay::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/time-table/week-days/' . $weekday->id, $weekdayUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE /api/time-table/week-days/{id}
   * @group timetable
   * @group weekday
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_weekday()
  {
    $weekday = WeekDay::factory()->create();
    $this->deleteJson('/api/time-table/week-days/' . $weekday->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/time-table/week-days/{id}
   * @group timetable
   * @group weekday
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_weekday()
  {
    $weekday = WeekDay::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/time-table/week-days/' . $weekday->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/time-table/week-days/{id}
   * @group timetable
   * @group weekday
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_weekday()
  {
    Permission::factory()->state(['name' => 'delete weekday'])->create();
    $this->user->givePermissionTo('delete weekday');
    $weekday = WeekDay::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/time-table/week-days/' . $weekday->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/time-table/week-days/{id}
   * @group timetable
   * @group weekday
   * @test
   * @group delete-request
   * @return void
   */
  public function weekday_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete weekday'])->create();
    $this->user->givePermissionTo('delete weekday');
    $weekday = WeekDay::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/time-table/week-days/' . $weekday->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(WeekDay::find($weekday->id));
  }
}



