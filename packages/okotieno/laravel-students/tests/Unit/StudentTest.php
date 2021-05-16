<?php


namespace Okotieno\Students\Tests\Unit;


use Okotieno\Students\Models\Student;
use Tests\TestCase;

class StudentTest extends TestCase
{
  /**
   * GET /api/students?limit=:limit
   * @group students
   * @group get-request
   * @test
   */
  public function unauthenticated_users_cannot_retrieve_recently_created_students()
  {
    Student::factory()->count(7)->create();
    $this->getJson('api/students?limit=5')
      ->assertStatus(401);

  }

  /**
   * GET /api/students?limit=:limit
   * @group students
   * @group get-request
   * @test
   */
  public function authenticated_users_can_retrieve_recently_created_students()
  {
    Student::factory()->count(7)->create();
    $response = $this->actingAs($this->user, 'api')->getJson('api/students?limit=5');
    $response->assertStatus(200);
    $response->assertJsonStructure([['id', 'first_name', 'last_name']]);
    $lastRecord = Student::orderBy('id', 'desc')->take(1)->first();
    $response->assertJsonFragment(['id' => $lastRecord->user->id]);
    $response->assertJsonFragment(['middle_name' => $lastRecord->user->middle_name]);

  }

  /**
   * GET /api/students?id=:id
   * @group students
   * @group get-request
   * @test
   */
  public function authenticated_users_can_query_student_by_id()
  {
    $student = Student::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/students?id='.$student->user->id);
    $response->assertStatus(200);
    $response->assertJsonStructure(['id', 'first_name', 'last_name']);
    $response->assertJsonFragment(['id' => $student->user->id]);
    $response->assertJsonFragment(['middle_name' => $student->user->middle_name]);

  }

  /**
   * GET /api/students?id=:id
   * @group students
   * @group get-request
   * @test
   */
  public function authenticated_users_can_query_student_by_text()
  {
    $student = Student::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->getJson('api/students?q='.$student->user->first_name);
    $response->assertStatus(200);
    $response->assertJsonStructure([['id', 'first_name', 'last_name']]);
    $response->assertJsonFragment(['id' => $student->user->id]);
    $response->assertJsonFragment(['middle_name' => $student->user->middle_name]);

  }

}
