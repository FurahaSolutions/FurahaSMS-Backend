<?php


namespace Okotieno\Students\Tests\Unit;


use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\AcademicYearUnitAllocation;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolStreams\Models\Stream;
use Okotieno\Students\Models\Student;
use Tests\TestCase;

class StudentAcademicsTest extends TestCase
{
  /**
   * GET /api/students/:student/academics
   * @group students
   * @group students-academics
   * @test
   */

  public function unauthenticated_users_cannot_retrieve_student_academics()
  {
    $student = Student::factory()->create();
    $this->getJson('api/students/' . $student->user->id . '/academics')
      ->assertUnauthorized();
  }

  /**
   * GET /api/students/:student/academics
   * @group students
   * @group students-academics
   * @test
   */

  public function error_404_if_user_is_non_student()
  {
    $user = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('api/students/' . $user->id . '/academics')
      ->assertNotFound();
  }

  /**
   * GET /api/students/:student/academics
   * @group students
   * @group students-academics
   * @test
   */

  public function authenticated_users_can_retrieve_empty_student_academics()
  {
    $student = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('api/students/' . $student->user->id . '/academics')
      ->assertOk();
  }

  /**
   * GET /api/students/:student/academics
   * @group students
   * @group students-academics
   * @test
   */

  public function authenticated_users_can_retrieve_student_academics()
  {
    $student = Student::factory()->create();
    $allocation = AcademicYearUnitAllocation::factory()->create();
    $student->unitAllocation()->save($allocation);
    $stream = Stream::factory()->create();
    $student->streams()->save($stream, [
      'academic_year_id' => $allocation->academic_year_id,
      'class_level_id' => $allocation->class_level_id
    ]);
    $this->actingAs($this->user, 'api')
      ->getJson('api/students/' . $student->user->id . '/academics')
      ->assertOk();
  }

  /**
   * POST /api/students/:student/academics
   * @group students
   * @group students-academics
   * @test
   */

  public function unauthenticated_users_cannot_assign_student_academics()
  {
    $student = Student::factory()->create();
    $this->postJson('api/students/' . $student->user->id . '/academics', [
      'stream_id' => Stream::factory()->create()->id,
      'academic_year_id' => AcademicYear::factory()->create()->id,
      'class_level_id' => ClassLevel::factory()->create()->id,
      'unit_levels' => AcademicYearUnitAllocation::factory()->count(2)->create()->pluck('id')->toArray()
    ])
      ->assertUnauthorized();
  }

  /**
   * POST /api/students/:student/academics
   * @group students
   * @group students-academics
   * @test
   */

  public function authorized_users_can_assign_student_academics()
  {
    Permission::factory()->state(['name' => 'allocate student academics'])->create();
    $this->user->givePermissionTo('allocate student academics');
    $student = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/students/' . $student->user->id . '/academics', [
        'stream_id' => Stream::factory()->create()->id,
        'academic_year_id' => AcademicYear::factory()->create()->id,
        'class_level_id' => ClassLevel::factory()->create()->id,
        'unit_levels' => AcademicYearUnitAllocation::factory()->count(2)->create()->pluck('id')->toArray()
      ])
      ->assertCreated();
  }

  /**
   * POST /api/students/:student/academics
   * @group students
   * @group students-academics
   * @test
   */

  public function unauthorized_users_cannot_assign_student_academics()
  {
    $student = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/students/' . $student->user->id . '/academics', [
        'stream_id' => Stream::factory()->create()->id,
        'academic_year_id' => AcademicYear::factory()->create()->id,
        'class_level_id' => ClassLevel::factory()->create()->id,
        'unit_levels' => AcademicYearUnitAllocation::factory()->count(2)->create()->pluck('id')->toArray()
      ])
      ->assertForbidden();
  }

  /**
   * GET /api/students/:student/academics/:academicYear
   * @group students
   * @group students-academics
   * @test
   */

  public function authenticated_users_can_retrieve_student_academic_year_academics()
  {
    $student = Student::factory()->create();
    $allocation = AcademicYearUnitAllocation::factory()->create();
    $student->unitAllocation()->save($allocation);
    $this->actingAs($this->user, 'api')
      ->getJson('api/students/' . $student->user->id . '/academics/' . $allocation->academic_year_id)
      ->assertOk();
  }

  /**
   * GET /api/students/:student/academics/:academicYear
   * @group students
   * @group students-academics
   * @test
   */

  public function authenticated_users_can_retrieve_student_academic_year_academics_filtered_by_class_level()
  {
    $student = Student::factory()->create();
    $allocations = AcademicYearUnitAllocation::factory()->count(2)->create();
    $student->unitAllocation()->save($allocations[0]);
    $student->unitAllocation()->save($allocations[1]);

    $url = 'api/students/' . $student->user->id . '/academics/' . $allocations[0]->academic_year_id
      . '?=class_level_id' . $allocations[0]->class_level_id;
    $this->actingAs($this->user, 'api')
      ->getJson($url)
      ->assertOk();
  }

  /**
   * PATCH /api/students/:student/academics/:academic_year
   * @group students
   * @group students-academics
   * @test
   */

  public function unauthenticated_users_cannot_update_student_academics()
  {
    $academicYear = AcademicYear::factory()->create();
    $student = Student::factory()->create();
    $this->patchJson('api/students/' . $student->user->id . '/academics/' . $academicYear->id, [
      'stream' => Stream::factory()->create()->id,
      'classLevelId' => ClassLevel::factory()->create()->id,
      'unitLevels' => AcademicYearUnitAllocation::factory()->count(2)->create()->pluck('id')->toArray()
    ])
      ->assertUnauthorized();
  }

  /**
   * PATCH /api/students/:student/academics
   * @group students
   * @group students-academics
   * @test
   */

  public function authorized_users_can_update_student_academics()
  {
    Permission::factory()->state(['name' => 'update student academics allocation'])->create();
    $this->user->givePermissionTo('update student academics allocation');
    $student = Student::factory()->create();
    $allocation = AcademicYearUnitAllocation::factory()->create();
    $student->unitAllocation()->save($allocation);
    $unitLevels = [['id' => $allocation->id, 'value' => false]];
    $this->actingAs($this->user, 'api')
      ->patchJson('api/students/' . $student->user->id . '/academics/' . $allocation->academic_year_id, [
        'stream' => Stream::factory()->create()->id,
        'classLevelId' => $allocation->class_level_id,
        'unitLevels' => $unitLevels
      ])
      ->assertOk();
  }

  /**
   * PATCH /api/students/:student/academics
   * @group students
   * @group students-academics
   * @test
   */

  public function unauthorized_users_cannot_update_student_academics()
  {
    $student = Student::factory()->create();
    $allocation = AcademicYearUnitAllocation::factory()->create();
    $student->unitAllocation()->save($allocation);
    $unitLevels = [['id' => $allocation->id, 'value' => false]];
    $this->actingAs($this->user, 'api')
      ->patchJson('api/students/' . $student->user->id . '/academics/' . $allocation->academic_year_id, [
        'stream' => Stream::factory()->create()->id,
        'classLevelId' => $allocation->class_level_id,
        'unitLevels' => $unitLevels
      ])
      ->assertForbidden();
  }
}
