<?php


namespace Okotieno\Procurement\Tests\Unit;


use App\Models\User;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\Procurement\Models\ProcurementBid;
use Okotieno\Procurement\Models\ProcurementRequest;
use Tests\TestCase;

class ProcurementRequestTest extends TestCase
{
  /**
   * POST /api/procurements/requests
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
   * POST /api/procurements/requests
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
   * POST /api/procurements/requests
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
   * PATCH /api/procurements/requests
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthorised_users_cannot_update_procurement_request()
  {
    $procurementRequest = ProcurementRequest::factory()->create();
    $procurementRequestUpdate = ProcurementRequest::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('api/procurements/requests/'.$procurementRequest->id, $procurementRequestUpdate)
      ->assertStatus(403);
  }


  /**
   * POST /api/procurements/tenders
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthenticated_users_cannot_create_procurement_tender()
  {
    $procurement = ProcurementRequest::factory()->create();
    $this->postJson('/api/procurements/tenders', [
      'description' => "Nicely done description",
      'expiryDatetime' => "2021-04-17T12:00",
      'expiry_datetime' => "2021-04-17T12:00",
      'procurement_request_id' => $procurement->id
    ])
      ->assertStatus(401);
  }

  /**
   * POST /api/procurements/tenders
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthorised_users_cannot_create_procurement_tender()
  {
    $procurement = ProcurementRequest::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/procurements/tenders',[
        'description' => "Nicely done description",
        'expiryDatetime' => "2021-04-17T12:00",
        'expiry_datetime' => "2021-04-17T12:00",
        'procurement_request_id' => $procurement->id
      ])
      ->assertStatus(403);
  }

  /**
   * POST /api/procurements/tenders
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function authorised_users_can_approve_create_procurement_tender()
  {
    Permission::factory()->state(['name' => 'create procurement tender'])->create();
    $this->user->givePermissionTo('create procurement tender');
    $procurement = ProcurementRequest::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/procurements/tenders', [
        'description' => "Nicely done description",
        'expiryDatetime' => "2021-04-17T12:00",
        'expiry_datetime' => "2021-04-17T12:00",
        'procurement_request_id' => $procurement->id
      ])
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
  }


  /**
   * POST /api/procurements/tenders
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthenticated_users_cannot_award_tender()
  {
    $bid = ProcurementBid::factory()->create();
    $bid->tender_id;
    $this->patchJson('api/procurements/tenders/'.$bid->tender_id.'/bids/'.$bid->id, [
      'awarded' => $this->faker->boolean
    ])
      ->assertStatus(401);
  }

  /**
   * POST /api/procurements/tenders
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthorised_users_cannot_award_tender()
  {
    $bid = ProcurementBid::factory()->create();
    $bid->tender_id;
    $this->actingAs($this->user, 'api')
      ->patchJson('api/procurements/tenders/'.$bid->tender_id.'/bids/'.$bid->id, [
        'awarded' => $this->faker->boolean
      ])
      ->assertStatus(403);
  }

  /**
   * POST /api/procurements/tenders
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function authorised_users_can_award_tender()
  {
    $bid = ProcurementBid::factory()->create();
    $bid->tender_id;
    Permission::factory()->state(['name' => 'award procurement tender'])->create();
    $this->user->givePermissionTo('award procurement tender');
    $this->actingAs($this->user, 'api')
      ->patchJson('api/procurements/tenders/'.$bid->tender_id.'/bids/'.$bid->id, [
        'awarded' => $this->faker->boolean
      ])
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
  }

  /**
   * PATCH /api/procurements/requests
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function unauthenticated_users_cannot_update_procurement_request()
  {
    $procurementRequest = ProcurementRequest::factory()->create();
    $procurementRequestUpdate = ProcurementRequest::factory()->make()->toArray();
    $this->patchJson('api/procurements/requests/'.$procurementRequest->id, $procurementRequestUpdate)
      ->assertStatus(401);
  }


  /**
   * PATCH /api/procurements/requests
   * @test
   * @group procurement
   * @group procurement-request
   * @group post-request
   */
  public function authorised_users_can_update_procurement_request()
  {
    $procurementRequest = ProcurementRequest::factory()->create();
    $procurementRequestUpdate = ProcurementRequest::factory()->make()->toArray();
    Permission::factory()->state(['name' => 'update procurement request'])->create();
    $this->user->givePermissionTo('update procurement request');
    $this->actingAs($this->user, 'api')
      ->patchJson('api/procurements/requests/'. $procurementRequest->id, $procurementRequestUpdate)
      ->assertStatus(200);
  }

  /**
   * PATCH /api/procurements/requests
   * @test
   * @group procurement
   * @group procurement-request-1
   * @group post-request
   */
  public function authorised_users_can_update_own_procurement_request()
  {
    $user = User::factory()->create();
    $procurementRequest = ProcurementRequest::factory()->state([
      'requested_by' => $user->id
    ])->create();
    $procurementRequestUpdate = ProcurementRequest::factory()->make()->toArray();
    $this->actingAs($user, 'api')
      ->patchJson('api/procurements/requests/'. $procurementRequest->id, $procurementRequestUpdate)
      ->assertStatus(200);
  }
}
