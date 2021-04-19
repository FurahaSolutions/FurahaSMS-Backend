<?php

use Okotieno\TimeTable\Controllers\AcademicYearTimeTableController;
use Okotieno\TimeTable\Controllers\TimeTableLessonsController;
use Okotieno\TimeTable\Controllers\TimeTableTimingsController;
use Okotieno\TimeTable\Controllers\TimingsTemplateController;
use Okotieno\TimeTable\Controllers\WeekDaysController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api')->group(function () {
    Route::apiResources([
      'time-table/time-table-timing-templates' => TimingsTemplateController::class,
      'time-table/week-days' => WeekDaysController::class,
      'academic-year/{academicYear:id}/time-tables' => AcademicYearTimeTableController::class,
      'academic-year/{academicYear}/time-tables/{timeTable}/lessons' => TimeTableLessonsController::class,
      'academic-year/{academicYear}/time-tables/{timeTable}/timings' => TimeTableTimingsController::class
    ]);
  });
});
