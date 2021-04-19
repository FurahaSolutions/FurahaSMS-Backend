<?php

namespace Okotieno\TimeTable\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\TimeTable\Models\TimeTableTimingTemplate;
use Tests\TestCase;


class TimeTableTimingTemplateTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $timeTableTimingTemplate;


  protected function setUp(): void
  {
    parent::setUp();
    $this->timeTableTimingTemplate = TimeTableTimingTemplate::factory()->make()->toArray();
  }

  /**
   * GET /api/time-table/time-table-timing-templates
   * @group timetable
   * @group timetable-template-timing
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_timetable_timing_templates()
  {
    $this->getJson('/api/time-table/time-table-timing-templates', $this->timeTableTimingTemplate)
      ->assertStatus(401);

  }

  /**
   * GET /api/time-table/time-table-timing-templates
   * @group timetable
   * @group timetable-template-timing
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_timetable_timing_templates()
  {
    TimeTableTimingTemplate::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/time-table/time-table-timing-templates', $this->timeTableTimingTemplate)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'description']]);

  }

  /**
   * GET /api/time-table/time-table-timing-templates/:id
   * @group timetable
   * @group timetable-template-timing
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_timetable_timing_template()
  {
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $this->getJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id, $this->timeTableTimingTemplate)
      ->assertStatus(401);

  }

  /**
   * GET /api/time-table/time-table-timing-templates/:id
   * @group timetable
   * @group timetable-template-timing
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_timetable_timing_template()
  {
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'description']);

  }


  /**
   * POST /api/time-table/time-table-timing-templates
   * @group timetable
   * @group timetable-template-timing
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_timetable_timing_template()
  {
    $this->postJson('/api/time-table/time-table-timing-templates', $this->timeTableTimingTemplate)
      ->assertStatus(401);

  }

  /**
   * POST /api/time-table/time-table-timing-templates
   * @group timetable
   * @group timetable-template-timing
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_timetable_timing_template()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/time-table/time-table-timing-templates', $this->timeTableTimingTemplate)
      ->assertStatus(403);
  }

  /**
   * POST /api/time-table/time-table-timing-templates
   * @group timetable
   * @group timetable-template-timing
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_timetable_timing_template()
  {
    Permission::factory()->state(['name' => 'create time table timing template'])->create();
    $this->user->givePermissionTo('create time table timing template');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/time-table/time-table-timing-templates', $this->timeTableTimingTemplate)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'description']]);
  }

  /**
   * POST /api/time-table/time-table-timing-templates
   * @group timetable
   * @group timetable-template-timing
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->timeTableTimingTemplate['description'] = '';
    Permission::factory()->state(['name' => 'create time table timing template'])->create();
    $this->user->givePermissionTo('create time table timing template');
    $this->actingAs($this->user, 'api')->postJson('/api/time-table/time-table-timing-templates', $this->timeTableTimingTemplate)
      ->assertStatus(422);
  }


  /**
   * POST /api/time-table/time-table-timing-templates
   * @group timetable
   * @group timetable-template-timing
   * @test
   * @group post-request
   * @return void
   */
  public function time_table_timing_template_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create time table timing template'])->create();
    $this->user->givePermissionTo('create time table timing template');
    $this->actingAs($this->user, 'api')->postJson('/api/time-table/time-table-timing-templates', $this->timeTableTimingTemplate)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'description']]);
    $timeTableTimingTemplate = TimeTableTimingTemplate::where('description', $this->timeTableTimingTemplate['description'])
      ->where('description', $this->timeTableTimingTemplate['description'])->first();
    $this->assertNotNull($timeTableTimingTemplate);
  }


  /**
   * PATCH /api/time-table/time-table-timing-templates/{id}
   * @group timetable
   * @group timetable-template-timing
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_timetable_timing_template()
  {
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $timeTableTimingTemplateUpdate = TimeTableTimingTemplate::factory()->make()->toArray();
    $res = $this->patchJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id, $timeTableTimingTemplateUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/time-table/time-table-timing-templates/{id}
   * @group timetable
   * @group timetable-template-timing-1
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_timetable_timing_template()
  {
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $timeTableTimingTemplateUpdate = TimeTableTimingTemplate::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id, $timeTableTimingTemplateUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/time-table/time-table-timing-templates/{id}
   * @group timetable
   * @group timetable-template-timing
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_timetable_timing_template()
  {
    Permission::factory()->state(['name' => 'update time table timing template'])->create();
    $this->user->givePermissionTo('update time table timing template');

    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $timeTableTimingTemplateUpdate = TimeTableTimingTemplate::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id, $timeTableTimingTemplateUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/time-table/time-table-timing-templates/{id}
   * @group timetable
   * @group timetable-template-timing
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update time table timing template'])->create();
    $this->user->givePermissionTo('update time table timing template');
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $timeTableTimingTemplateUpdate = TimeTableTimingTemplate::factory()->state(['description' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id, $timeTableTimingTemplateUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/time-table/time-table-timing-templates/{id}
   * @group timetable
   * @group timetable-template-timing
   * @test
   * @group patch-request
   * @return void
   */
  public function time_table_timing_template_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update time table timing template'])->create();
    $this->user->givePermissionTo('update time table timing template');
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $timeTableTimingTemplateUpdate = TimeTableTimingTemplate::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id, $timeTableTimingTemplateUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'description']]);
  }

  /**
   * DELETE /api/time-table/time-table-timing-templates/{id}
   * @group timetable
   * @group timetable-template-timing
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_timetable_timing_template()
  {
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $this->deleteJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/time-table/time-table-timing-templates/{id}
   * @group timetable
   * @group timetable-template-timing
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_timetable_timing_template()
  {
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/time-table/time-table-timing-templates/{id}
   * @group timetable
   * @group timetable-template-timing
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_timetable_timing_template()
  {
    Permission::factory()->state(['name' => 'delete time table timing template'])->create();
    $this->user->givePermissionTo('delete time table timing template');
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/time-table/time-table-timing-templates/{id}
   * @group timetable
   * @group timetable-template-timing
   * @test
   * @group delete-request
   * @return void
   */
  public function time_table_timing_template_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete time table timing template'])->create();
    $this->user->givePermissionTo('delete time table timing template');
    $timeTableTimingTemplate = TimeTableTimingTemplate::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/time-table/time-table-timing-templates/' . $timeTableTimingTemplate->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(TimeTableTimingTemplate::find($timeTableTimingTemplate->id));
  }
}



