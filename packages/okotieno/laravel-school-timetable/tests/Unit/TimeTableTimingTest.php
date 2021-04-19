<?php

namespace Okotieno\TimeTable\Tests\Unit;

use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\TimeTable\Models\TimeTable;
use Tests\TestCase;


class TimeTableTimingTest extends TestCase
{

  private $timeTable;
  private $academicYear;
  private $url;


  protected function setUp(): void
  {
    parent::setUp();
    $this->academicYear = AcademicYear::factory()->create();
    $this->timeTable = TimeTable::factory()
      ->state(['academic_year_id' => $this->academicYear->id])
      ->create();
    $this->url = 'api/academic-year/' . $this->academicYear->id . '/time-tables/' . $this->timeTable->id . "/timings";
  }

  /**
   * GET /api/academic-year/:academicYear/time-tables/:time-table/timings
   * @group timetable
   * @group timetable-timing
   * @group get-request
   * @test
   * @return void
   */

  public function unauthenticated_users_cannot_retrieve_academic_year_time_table()
  {
    $this->getJson($this->url)->assertStatus(401);
  }

  /**
   * GET /api/academic-year/:academicYear/time-tables/:time-table/timings
   * @group timetable
   * @group timetable-timing
   * @group get-request
   * @test
   * @return void
   */

  public function authenticated_users_cannot_retrieve_academic_year_time_table()
  {
    $this->actingAs($this->user, 'api')->getJson($this->url)->assertStatus(200);
  }

}



