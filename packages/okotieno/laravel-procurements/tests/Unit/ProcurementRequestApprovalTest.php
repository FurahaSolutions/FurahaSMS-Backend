<?php


namespace Okotieno\Procurement\Tests\Unit;


use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\Procurement\Models\ProcurementRequest;
use Tests\TestCase;

class ProcurementRequestApprovalTest extends TestCase
{
  /**
   * GET /api/procurements/requests/pending-approval
   * @test
   * @group procurement
   * @group procurement-request
   * @group get-request
   */
  public function unauthenticated_users_cannot_retrieve_procurement_request()
  {
    $this->getJson('/api/procurements/requests/pending-approval')
      ->assertStatus(401);
  }

  /**
   * GET /api/procurements/requests/pending-approval
   * @test
   * @group procurement
   * @group procurement-request-approval
   * @group get-request
   */
  public function authorised_users_cannot_retrieve_procurement_request()
  {
    $this->actingAs($this->user, 'api')
      ->getJson('/api/procurements/requests/pending-approval')
      ->assertStatus(403);
  }

  /**
   * POST /api/procurements/requests/pending-approval
   * @test
   * @group procurement
   * @group procurement-request-approval
   * @group post-request
   */
  public function unauthenticated_users_cannot_approve_procurement_request()
  {
    $procurement = ProcurementRequest::factory()->create();
    $this->postJson('/api/procurements/requests/pending-approval', ['approve' => true, 'procurement_request_id' => $procurement->id])
      ->assertStatus(401);
  }

  /**
   * POST /api/procurements/requests/pending-approval
   * @test
   * @group procurement
   * @group procurement-request-approval
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
   * POST /api/procurements/requests/pending-approval
   * @test
   * @group procurement
   * @group procurement-request-approval
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
