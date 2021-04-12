<?php


namespace Okotieno\Procurement\Tests\Unit;


use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\Procurement\Models\ProcurementRequest;
use Tests\TestCase;

class ProcurementRequestTest extends TestCase
{
  /**
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthenticated_users_cannot_make_procurement_request()
  {
    $this->postJson('api/procurements/requests', [])
      ->assertStatus(401);
  }

  /**
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthorised_users_cannot_make_procurement_request()
  {
    $procurementRequest = ProcurementRequest::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')->postJson('api/procurements/requests', $procurementRequest)
      ->assertStatus(403);
  }

  /**
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function authorised_users_can_make_procurement_request()
  {
    Permission::factory()->state(['name' => 'make procurement request'])->create();
    $this->user->givePermissionTo('make procurement request');
    $procurementRequest = ProcurementRequest::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')->postJson('api/procurements/requests', $procurementRequest)
      ->assertStatus(200);
  }
  /**
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthenticated_users_cannot_approve_procurement_request()
  {
    $procurement = ProcurementRequest::factory()->create();
    $this->postJson('/api/procurements/requests/pending-approval', ['approve' => true, 'procurement_request_id' => $procurement->id])
      ->assertStatus(401);
  }

  /**
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthorised_users_cannot_approve_procurement_request()
  {
    $procurement = ProcurementRequest::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/procurements/requests/pending-approval', ['approve' => true, 'procurement_request_id' => $procurement->id])
      ->assertStatus(403);
  }

  /**
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function authorised_users_can_approve_procurement_request()
  {
    Permission::factory()->state(['name' => 'approve procurement request'])->create();
    $this->user->givePermissionTo('approve procurement request');
    $procurement = ProcurementRequest::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/procurements/requests/pending-approval', ['approve' => true, 'procurement_request_id' => $procurement->id])
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
  }
}
