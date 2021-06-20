<?php


namespace Okotieno\SchoolAccounts\Tests\Unit;


use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolAccounts\Models\FinancialCost;
use Okotieno\SchoolAccounts\Models\FinancialCostItem;
use Tests\TestCase;

class FinancialCostTest extends TestCase
{
  /**
   * GET /api/accounts/financial-costs
   * @group accounts
   * @group financial_cost
   * @test
   */
  public function unauthenticated_users_cannot_retrieve_financial_costs()
  {
    $this->getJson('api/accounts/financial-costs')
      ->assertUnauthorized();
  }

  /**
   * GET /api/accounts/financial-costs
   * @group accounts
   * @group financial_cost
   * @test
   */
  public function authenticated_users_can_retrieve_financial_costs()
  {
    FinancialCost::factory()->count(2)->create();
    $this->actingAs($this->user, 'api')->getJson('api/accounts/financial-costs')
      ->assertOk()
      ->assertJsonStructure([['name', 'id']]);
  }

  /**
   * POST /api/accounts/financial-costs
   * @group accounts
   * @group financial_cost
   * @test
   */
  public function unauthenticated_users_cannot_create_financial_costs()
  {
    $this->postJson('/api/accounts/financial-costs', [])
      ->assertUnauthorized();
  }

  /**
   * POST /api/accounts/financial-costs
   * @group accounts
   * @group financial_cost
   * @test
   */
  public function unauthorized_users_cannot_create_financial_costs()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('/api/accounts/financial-costs', [])
      ->assertForbidden();
  }

  /**
   * POST /api/accounts/financial-costs
   * @group accounts
   * @group financial_cost
   * @test
   */
  public function authorized_users_can_create_financial_costs()
  {
    Permission::factory()->state(['name' => 'create financial cost'])->create();
    $this->user->givePermissionTo('create financial cost');

    $costs = FinancialCost::factory()->count(2)->make()->toArray();
    foreach ($costs as $key => $cost) {
      $costs[$key]['costItems'] = FinancialCostItem::factory()->count(2)->make()->toArray();
    }
    $this->actingAs($this->user, 'api')
      ->postJson('/api/accounts/financial-costs', $costs)
      ->assertCreated();

    $costs = FinancialCost::factory()->count(2)->create()->toArray();
    foreach ($costs as $key => $cost) {
      $costs[$key]['costItems'] = FinancialCostItem::factory()->count(2)->make()->toArray();
    }
    $this->actingAs($this->user, 'api')
      ->postJson('/api/accounts/financial-costs', $costs)
      ->assertCreated();

    $costs = FinancialCost::factory()->count(2)->create()->toArray();
    foreach ($costs as $key => $cost) {
      $costs[$key]['costItems'] = FinancialCostItem::factory()
        ->state(['financial_cost_id' => $cost['id']])->count(2)->create()->toArray();
    }
    $this->actingAs($this->user, 'api')
      ->postJson('/api/accounts/financial-costs', $costs)
      ->assertCreated();
  }

  /**
   * DELETE /api/accounts/financial-costs/:financial_cost
   * @group accounts
   * @group financial_cost
   * @test
   */
  public function unauthenticated_users_cannot_delete_financial_costs()
  {
    $financialCost = FinancialCost::factory()->create();
    $this->deleteJson('/api/accounts/financial-costs/' . $financialCost->id)
      ->assertUnauthorized();
  }

  /**
   * POST /api/accounts/financial-costs
   * @group accounts
   * @group financial_cost
   * @test
   */
  public function unauthorized_users_cannot_delete_financial_costs()
  {
    $financialCost = FinancialCost::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/accounts/financial-costs/' . $financialCost->id)
      ->assertForbidden();
  }

  /**
   * POST /api/accounts/financial-costs
   * @group accounts
   * @group financial_cost
   * @test
   */
  public function authorized_users_can_delete_financial_costs()
  {
    Permission::factory()->state(['name' => 'delete financial cost'])->create();
    $this->user->givePermissionTo('delete financial cost');
    $financialCost = FinancialCost::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/accounts/financial-costs/' . $financialCost->id)
      ->assertOk();
  }
}
