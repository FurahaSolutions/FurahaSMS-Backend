<?php

namespace Okotieno\SchoolStreams\Tests\Unit;

use Okotieno\Students\Models\Student;
use Tests\TestCase;

class StudentStreamTest extends TestCase
{
  /**
   * GET /api/students/:user/streams
   * @group stream
   * @group student_streams
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_streams()
  {
    $student = Student::factory()->create();
    $this->getJson('/api/students/'. $student->user->id.'/streams')
      ->assertUnauthorized();

  }

  /**
   * GET /api/students/:user/streams
   * @group stream
   * @group student_streams
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_streams()
  {
    $student = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('/api/students/'. $student->user->id.'/streams')
      ->assertOk();

  }
}
