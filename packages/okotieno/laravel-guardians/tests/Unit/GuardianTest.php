<?php


namespace Okotieno\Guardians\Tests\Unit;


use Okotieno\Guardians\Models\Guardian;
use Tests\TestCase;

class GuardianTest extends TestCase
{
  /**
   * GET /api/guardians/:guardian
   * @test
   * @group guardians
   * @get-request
   */
  public function unauthenticated_users_cannot_access_guardian_details()
  {
    $guardian = Guardian::factory()->create();
    $this->getJson('api/guardians/' . $guardian->user->id)
      ->assertStatus(401);
  }

  /**
   * GET /api/guardians/:guardian
   * @test
   * @group guardians
   * @get-request
   */
  public function authenticated_users_can_access_guardian_details()
  {
    $guardian = Guardian::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('api/guardians/' . $guardian->user->id)->assertStatus(200)
      ->assertJsonStructure(['id', 'firstName', 'lastName', 'genderName', 'religionName']);
  }
}
