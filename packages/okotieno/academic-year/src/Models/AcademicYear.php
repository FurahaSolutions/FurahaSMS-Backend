<?php

namespace Okotieno\AcademicYear\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\AcademicYear\Database\Factories\AcademicYearFactory;
use Okotieno\AcademicYear\Requests\CreateAcademicYearRequest;
use Okotieno\AcademicYear\Traits\Archivable;
use Okotieno\AcademicYear\Traits\HasHoliday;
use Okotieno\SchoolAccounts\Traits\hasFinancialYearPlans;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\TimeTable\Traits\HasTimeTables;

/**
 * @property mixed name
 */
class AcademicYear extends Model
{
  use hasFinancialYearPlans, HasTimeTables, HasHoliday, HasFactory, Archivable;

  public $timestamps = false;
  protected $fillable = ['name', 'start_date', 'end_date'];

  public static function createAcademicYear(CreateAcademicYearRequest $request)
  {
    $academicYear = self::create([
      'name' => $request->name,
      'start_date' => Carbon::createFromDate($request->start_date),
      'end_date' => Carbon::createFromDate($request->end_date)
    ]);
    if ($request->class_levels) {
      foreach ($request->class_levels as $class_level) {
        if (key_exists('value', $class_level) && $class_level['value'] == true) {
          if (key_exists('subject_levels', $class_level)) {
            foreach ($class_level['subject_levels'] as $subject_level) {
              AcademicYearUnitAllocation::allocate($academicYear->id, $class_level['class_level_id'], $subject_level);
            }
          }
        }

      }
    }

    $academicYearStartDate = $academicYear->start_date;
    $academicYearEndDate = $academicYear->end_date;
    $years = range($academicYearStartDate->year, $academicYearEndDate->year);
    foreach (Holiday::all() as $holiday) {
      $holidayCarbonDate = Carbon::createFromDate($holiday->occurs_on);
      $month = $holidayCarbonDate->month;
      $date = $holidayCarbonDate->month;
      foreach ($years as $year) {
        $holidayDate = Carbon::createFromDate($year, $month, $date);
        if ($holidayDate >= $academicYearStartDate && $holidayDate <= $academicYearEndDate) {
          $academicYear->holidays()->save($holiday, [
            'confirmed' => $holiday->confirmation_variance === 0,
            'date' => $holidayDate
          ]);
        }
      }
    }
    return $academicYear;
  }

  public static function newFactory(): Factory
  {
    return AcademicYearFactory::new();
  }

  public function classLevels()
  {
    return $this->belongsToMany(
      ClassLevel::class, 'academic_year_unit_allocations')->withPivot([
      'unit_id'
    ]);
  }

  public function classAllocations()
  {
    return $this->hasMany(AcademicYearUnitAllocation::class);
  }

}
