<?php


namespace Okotieno\TimeTable\Tests\Unit;


use App\Models\User;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\Unit;
use Okotieno\SchoolStreams\Models\Stream;
use Okotieno\TimeTable\Models\TimeTable;
use Okotieno\TimeTable\Models\TimeTableTiming;

class TimeTableLessonTest extends \Tests\TestCase
{
  /**
   * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
   */
  private $academicYear;
  private $timeTable;
  private $url;

  protected function setUp(): void
  {
    parent::setUp();
    $this->academicYear = AcademicYear::factory()->create();
    $this->timeTable = TimeTable::factory()->state([
      'academic_year_id' => $this->academicYear->id
    ])->create();
    $this->url = '/api/academic-year/' . $this->academicYear->id . 'academic-year/time-tables/' . $this->timeTable->id . '/lessons';
  }

  /**
   * POST /api/academic-year/:academic-year/time-tables/:timeTable/lessons
   * @group post-request
   * @group time-table
   * @group time-table-lessons
   * @test
   * */
  public function unauthenticated_users_should_not_save_time_table()
  {

    $this->postJson($this->url, [])
      ->assertStatus(401);
  }

  /**
   * POST /api/academic-year/:academic-year/time-tables/:timeTable/lessons
   * @group post-request
   * @group time-table
   * @group time-table-lessons
   * @test
   * */
  public function unauthorised_users_should_not_save_time_table()
  {
    $this->actingAs($this->user, 'api')->postJson($this->url, [])
      ->assertStatus(403);
  }

  /**
   * POST /api/academic-year/:academic-year/time-tables/:timeTable/lessons
   * @group post-request
   * @group time-table
   * @group time-table-lessons
   * @test
   * */
  public function authorised_users_can_save_time_table()
  {
    $timeValue = TimeTableTiming::factory()->create();

    $timetableArray = [
      [
        'timeId' => TimeTableTiming::factory()->create()->id,
        'streamId' => Stream::factory()->create()->id,
        'teacherId' => User::factory()->create()->id,
        'dayOfWeekId' => 1,
        'subjectId' => Unit::factory()->create()->id,
        'classLevelId' => ClassLevel::factory()->create()->id,
        'roomId' => 1
      ],
      [
        'timeId' => TimeTableTiming::factory()->create()->id,
        'streamId' => Stream::factory()->create()->id,
        'teacherId' => User::factory()->create()->id,
        'dayOfWeekId' => 1,
        'subjectId' => Unit::factory()->create()->id,
        'classLevelId' => ClassLevel::factory()->create()->id,
        'roomId' => 1
      ],
      [
        'classLevelName' => ClassLevel::factory()->create()->abbreviation,
        'dayOfWeekName' => 'Mon',
        'streamName' => Stream::factory()->create()->abbreviation,
        'timeValue' => $timeValue->start . ' - ' . $timeValue->end,
        'teacherId' => NULL,
        'roomId' => NULL,
        'subjectId' => Unit::factory()->create()->id,
      ]
    ];

    Permission::factory()->state(['name' => 'create timetable lesson'])->create();
    $this->user->givePermissionTo('create timetable lesson');
    $this->actingAs($this->user, 'api')->postJson($this->url, $timetableArray)
      ->assertStatus(200);
  }
}
