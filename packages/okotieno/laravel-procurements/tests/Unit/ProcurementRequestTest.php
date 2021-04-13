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
}
