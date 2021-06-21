<?php

namespace Okotieno\SchoolAccounts\Tests\Unit;

use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
  /**
   * POST /api/accounts/payment-methods
   * @group accounts
   * @group payment-methods
   * @test
   */
  public function unauthenticated_users_cannot_retrieve_payment_methods()
  {
    $this->getJson('/api/accounts/payment-methods')
      ->assertUnauthorized();
  }

  /**
   * POST /api/accounts/payment-methods
   * @group accounts
   * @group payment-methods
   * @test
   */
  public function authenticated_users_can_retrieve_payment_methods()
  {
    $this->actingAs($this->user, 'api')->getJson('/api/accounts/payment-methods')
      ->assertOk();
  }
}
