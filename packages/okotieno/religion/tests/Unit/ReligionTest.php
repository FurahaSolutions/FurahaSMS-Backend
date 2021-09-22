<?php


namespace Okotieno\Gender\Tests\Unit;


use Tests\TestCase;

class ReligionTest extends TestCase
{
  /**
   * GET /api/e-learning/courses
   * @group get-request
   * @group gender
   * @test
   * */
  public function authenticated_users_can_retrieve_gender()
  {
    $this->actingAs($this->user, 'api')->get('api/religions')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);
  }
}
