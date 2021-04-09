<?php

use Okotieno\AcademicYear\Controllers\AcademicYearApiController;
use Okotieno\AcademicYear\Controllers\AcademicYearController;
use Okotieno\AcademicYear\Controllers\AcademicYearArchivesController;
use Okotieno\AcademicYear\Controllers\AcademicYearUnitLevelController;
use Okotieno\AcademicYear\Controllers\HolidayController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api')->group(function () {
    Route::resource('/academic-years/holidays', HolidayController::class);
    Route::resource('/academic-years', AcademicYearController::class);
    Route::post('/academic-years/{academicYear}/close/{closeItem}', [AcademicYearArchivesController::class, 'close']);
  });

  Route::get('/api/academic-years/all', [AcademicYearApiController::class, 'getAll']);
  Route::resource('/api/academic-years/{academicYear}/unit-levels', AcademicYearUnitLevelController::class);
  Route::get('/api/academic-years/{academicYear}/semesters', [AcademicYearApiController::class, 'semesters']);
});

