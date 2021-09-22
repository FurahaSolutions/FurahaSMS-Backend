<?php


namespace Okotieno\Gender\Tests\Unit;


use Tests\TestCase;

class GenderTest extends TestCase
{
  /**
   * GET /api/e-learning/courses
   * @group get-request
   * @group gender
   * @test
   * */
  public function authenticated_users_can_retrieve_gender()
  {
    $this->actingAs($this->user, 'api')->get('api/genders')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name']]);
  }
}
