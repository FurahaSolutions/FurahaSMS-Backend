<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{

  protected function setUp(): void
  {
    parent::setUp();

  }

  /**
   * GET api/user
   * @group users
   * @test
   */
  public function unauthenticated_users_cannot_get_users()
  {
    $name = $this->faker->firstName;
    User::factory()->state(['first_name' => $name])->create();
    $this->getJson('api/users?name=' . $name)
      ->assertStatus(401);
  }

  /**
   * GET api/user
   * @group users
   * @test
   */
  public function authenticated_users_can_get_users()
  {
    $name = $this->faker->firstName;
    User::factory()->state(['first_name' => $name])->create();
    $this->actingAs($this->user, 'api')
      ->getJson('api/users?name=' . $name)
      ->assertStatus(200);
  }

}


