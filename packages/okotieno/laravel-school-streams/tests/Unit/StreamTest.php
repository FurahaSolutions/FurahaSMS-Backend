<?php

namespace Okotieno\SchoolStreams\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolStreams\Models\Stream;
use Tests\TestCase;


class StreamTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $stream;


  protected function setUp(): void
  {
    parent::setUp();
    $this->stream = Stream::factory()->make()->toArray();
  }

  /**
   * GET /api/class-streams
   * @group stream
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_streams()
  {
    $this->getJson('/api/class-streams', $this->stream)
      ->assertStatus(401);

  }

  /**
   * GET /api/class-streams
   * @group stream
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_streams()
  {
    Stream::factory()->count(3)->create();
    $this->actingAs($this->user, 'api')->getJson('/api/class-streams', $this->stream)
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);

  }

  /**
   * GET /api/class-streams/:id
   * @group stream
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_stream()
  {
    $stream = Stream::factory()->create();
    $this->getJson('/api/class-streams/' . $stream->id, $this->stream)
      ->assertStatus(401);

  }

  /**
   * GET /api/class-streams/:id
   * @group stream
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_stream()
  {
    $stream = Stream::factory()->create();
    $this->actingAs($this->user, 'api')->getJson('/api/class-streams/' . $stream->id)
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'name']);

  }


  /**
   * POST /api/class-streams
   * @group stream
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_stream()
  {
    $this->postJson('/api/class-streams', $this->stream)
      ->assertStatus(401);

  }

  /**
   * POST /api/class-streams
   * @group stream
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_stream()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/class-streams', $this->stream)
      ->assertStatus(403);
  }

  /**
   * POST /api/class-streams
   * @group stream
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_stream()
  {
    Permission::factory()->state(['name' => 'create class stream'])->create();
    $this->user->givePermissionTo('create class stream');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/class-streams', $this->stream)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * POST /api/class-streams
   * @group stream
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->stream['name'] = '';
    Permission::factory()->state(['name' => 'create class stream'])->create();
    $this->user->givePermissionTo('create class stream');
    $this->actingAs($this->user, 'api')->postJson('/api/class-streams', $this->stream)
      ->assertStatus(422);
  }


  /**
   * POST /api/class-streams
   * @group stream
   * @test
   * @group post-request
   * @return void
   */
  public function stream_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create class stream'])->create();
    $this->user->givePermissionTo('create class stream');
    $this->actingAs($this->user, 'api')->postJson('/api/class-streams', $this->stream)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $stream = Stream::where('name', $this->stream['name'])
      ->where('name', $this->stream['name'])->first();
    $this->assertNotNull($stream);
  }


  /**
   * PATCH /api/class-streams/{id}
   * @group stream
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_stream()
  {
    $stream = Stream::factory()->create();
    $streamUpdate = Stream::factory()->make()->toArray();
    $res = $this->patchJson('/api/class-streams/' . $stream->id, $streamUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/class-streams/{id}
   * @group stream
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_update_stream()
  {
    $stream = Stream::factory()->create();
    $streamUpdate = Stream::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/class-streams/' . $stream->id, $streamUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/class-streams/{id}
   * @group stream
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_stream()
  {
    Permission::factory()->state(['name' => 'update class stream'])->create();
    $this->user->givePermissionTo('update class stream');

    $stream = Stream::factory()->create();
    $streamUpdate = Stream::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/class-streams/' . $stream->id, $streamUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/class-streams/{id}
   * @group stream
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update class stream'])->create();
    $this->user->givePermissionTo('update class stream');
    $stream = Stream::factory()->create();
    $streamUpdate = Stream::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/class-streams/' . $stream->id, $streamUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/class-streams/{id}
   * @group stream
   * @test
   * @group patch-request
   * @return void
   */
  public function stream_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update class stream'])->create();
    $this->user->givePermissionTo('update class stream');
    $stream = Stream::factory()->create();
    $streamUpdate = Stream::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/class-streams/' . $stream->id, $streamUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE /api/class-streams/{id}
   * @group stream
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_stream()
  {
    $stream = Stream::factory()->create();
    $this->deleteJson('/api/class-streams/' . $stream->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/class-streams/{id}
   * @group stream
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_stream()
  {
    $stream = Stream::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/class-streams/' . $stream->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/class-streams/{id}
   * @group stream
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_stream()
  {
    Permission::factory()->state(['name' => 'delete class stream'])->create();
    $this->user->givePermissionTo('delete class stream');
    $stream = Stream::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/class-streams/' . $stream->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/class-streams/{id}
   * @group stream
   * @test
   * @group delete-request
   * @return void
   */
  public function stream_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete class stream'])->create();
    $this->user->givePermissionTo('delete class stream');
    $stream = Stream::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/class-streams/' . $stream->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(Stream::find($stream->id));
  }
}



