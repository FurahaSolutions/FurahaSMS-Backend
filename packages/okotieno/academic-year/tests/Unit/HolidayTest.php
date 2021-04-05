<?php

namespace Okotieno\holiday\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\AcademicYear\Models\Holiday;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;


class HolidayTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $holiday;


  protected function setUp(): void
  {
    parent::setUp();
    $this->holiday = Holiday::factory()->make()->toArray();
  }

  /**
   * POST /academic-year/holidays
   * @group holiday
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_holiday()
  {
    $this->postJson('/api/academic-years/holidays', $this->holiday)
      ->assertStatus(401);

  }

  /**
   * POST /academic-year/holidays
   * @group holiday
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_holiday()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/academic-years/holidays', $this->holiday)
      ->assertStatus(403);
  }

  /**
   * POST /academic-year/holidays
   * @group holiday
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_holiday()
  {
    Permission::factory()->state(['name' => 'create holiday']);
    $this->user->givePermissionTo('create holiday');
    $response = $this->actingAs($this->user, 'api')->postJson('/api/academic-years/holidays', $this->holiday);
    $response->assertStatus(201);
  }

  /**
   * POST /academic-year/holidays
   * @group holiday
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->holiday['name'] = '';
    Permission::factory()->state(['name' => 'create holiday']);
    $this->user->givePermissionTo('create holiday');
    $this->actingAs($this->user, 'api')->postJson('/api/academic-years/holidays', $this->holiday)
      ->assertStatus(422);
  }

  /**
   * POST /academic-year/holidays
   * @group holiday
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_occurs_on_not_provided()
  {
    $this->holiday['occurs_on'] = '';
    Permission::factory()->state(['name' => 'create holiday']);
    $this->user->givePermissionTo('create holiday');
    $this->actingAs($this->user, 'api')->postJson('/api/academic-years/holidays', $this->holiday)
      ->assertStatus(422);
  }


  /**
   * POST /academic-year/holidays
   * @group holiday
   * @test
   * @group post-request
   * @return void
   */
  public function should_throw_error_if_date_format_is_invalid()
  {
    $this->holiday['occurs_on'] = '01-01-2017';
    Permission::factory()->state(['name' => 'create holiday']);
    $this->user->givePermissionTo('create holiday');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/academic-years/holidays', $this->holiday)
      ->assertStatus(422);
  }

  /**
   * POST /academic-year/holidays
   * @group holiday
   * @test
   * @group post-request
   * @return void
   */
  public function holiday_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create holiday']);
    $this->user->givePermissionTo('create holiday');
    $this->actingAs($this->user, 'api')->postJson('/api/academic-years/holidays', $this->holiday)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name', 'occurs_on']]);
    $holiday = Holiday::where('name', $this->holiday['name'])
      ->where('name', $this->holiday['name'])->first();
    $this->assertNotNull($holiday);
  }


  /**
   * PATCH /academic-year/holidays/{id}
   * @group holiday
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_holiday()
  {
    $holiday = Holiday::factory()->create();
    $holidayUpdate = Holiday::factory()->make()->toArray();
    $res = $this->patchJson('/api/academic-years/holidays/' . $holiday->id, $holidayUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /academic-year/holidays/{id}
   * @group holiday
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_update_holiday()
  {
    $holiday = Holiday::factory()->create();
    $holidayUpdate = Holiday::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/holidays/' . $holiday->id, $holidayUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /academic-year/holidays/{id}
   * @group holiday
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_holiday()
  {
    $holiday = Holiday::factory()->create();
    $holidayUpdate = Holiday::factory()->make()->toArray();
    $this->user->permissions()->create(['name' => 'update holiday']);
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/holidays/' . $holiday->id, $holidayUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /academic-year/holidays/{id}
   * @group holiday
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    $holiday = Holiday::factory()->create();
    $holidayUpdate = Holiday::factory()->state(['name' => ''])->make()->toArray();
    $this->user->permissions()->create(['name' => 'update holiday']);
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/holidays/' . $holiday->id, $holidayUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /academic-year/holidays/{id}
   * @group holiday
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_occurs_on_not_provided_on_update()
  {
    $holiday = Holiday::factory()->create();
    $holidayUpdate = Holiday::factory()->state(['occurs_on' => ''])->make()->toArray();
    $this->user->permissions()->create(['name' => 'update holiday']);
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/holidays/' . $holiday->id, $holidayUpdate)
      ->assertStatus(422);
  }


  /**
   * PATCH /academic-year/holidays/{id}
   * @group holiday
   * @group patch-request
   * @test
   * @return void
   */
  public function should_throw_error_if_date_format_is_invalid_on_update()
  {
    $holiday = Holiday::factory()->create();
    $holidayUpdate = Holiday::factory()->state(['occurs_on' => '01-01-2017'])->make()->toArray();
    $this->holiday['occurs_on'] = '01-01-2017';
    $this->user->permissions()->create(['name' => 'update holiday']);
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/holidays/' . $holiday->id, $holidayUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /academic-year/holidays/{id}
   * @group holiday
   * @test
   * @group patch-request
   * @return void
   */
  public function holiday_should_be_updated_after_successful_call()
  {
    $holiday = Holiday::factory()->create();
    $holidayUpdate = Holiday::factory()->make()->toArray();
    $this->user->permissions()->create(['name' => 'update holiday']);
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/academic-years/holidays/' . $holiday->id, $holidayUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name', 'occurs_on']]);
  }

  /**
   * DELETE/academic-year/holidays/{id}
   * @group holiday
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_holiday()
  {
    $holiday = Holiday::factory()->create();
    $this->deleteJson('/api/academic-years/holidays/' . $holiday->id)
      ->assertStatus(401);

  }

  /**
   * DELETE/academic-year/holidays/{id}
   * @group holiday
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_holiday()
  {
    $holiday = Holiday::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/academic-years/holidays/' . $holiday->id)
      ->assertStatus(403);
  }

  /**
   * DELETE/academic-year/holidays/{id}
   * @group holiday
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_holiday()
  {
    $holiday = Holiday::factory()->create();
    $this->user->permissions()->create(['name' => 'delete holiday']);
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/academic-years/holidays/' . $holiday->id)
      ->assertStatus(200);
  }

  /**
   * DELETE/academic-year/holidays/{id}
   * @group holiday
   * @test
   * @group delete-request
   * @return void
   */
  public function holiday_should_be_deleted_after_successful_call()
  {
    $holiday = Holiday::factory()->create();
    $this->user->permissions()->create(['name' => 'delete holiday']);
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/academic-years/holidays/' . $holiday->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(Holiday::find($holiday->id));
  }
}



