<?php


namespace Okotieno\Procurement\Tests;


use Okotieno\Procurement\Models\ProcurementBid;
use Okotieno\Procurement\Models\ProcurementTender;
use Tests\TestCase;

class ProcurementTenderTest extends TestCase
{
  /**
   * GET /api/procurements/tenders?awarded=1
   * @test
   * @group procurement
   * @group procurement-tenders
   *
   */
  public function authenticated_users_can_retrieve_awarded_tenders()
  {
    $tender = ProcurementTender::factory()->create();
    $bid = ProcurementBid::factory()->state(['awarded' => true])->create();
    $tender->procurementTenderBids()->save(ProcurementBid::factory()->state(['awarded' => true])->create());
    $tender->procurementTenderBids()->save(ProcurementBid::factory()->state(['awarded' => false])->create());

    $this->actingAs($this->user, 'api')
      ->getJson('/api/procurements/tenders?awarded=1')
      ->assertStatus(200);
  }
}
