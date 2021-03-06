<?php

//use Okotieno\AcademicYear\Controllers\AcademicYearApiController;
use Illuminate\Support\Facades\Route;
use Okotieno\AcademicYear\Controllers\AcademicYearArchivesController;
use Okotieno\AcademicYear\Controllers\AcademicYearController;
use Okotieno\AcademicYear\Controllers\AcademicYearUnitLevelController;
use Okotieno\AcademicYear\Controllers\HolidayController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api')->group(function () {
    Route::get('/academic-years/{academicYear}/archivable-items', [AcademicYearArchivesController::class, 'academicYearArchivableItems']);
    Route::get('/academic-years/archivable-items', [AcademicYearArchivesController::class, 'archivableItems']);
    Route::post('/academic-years/{academicYear}/archive', [AcademicYearArchivesController::class, 'archive']);
    Route::post('/academic-years/{academicYear}/unarchive', [AcademicYearArchivesController::class, 'unarchive']);
    Route::post('/academic-years/{academicYear}/restore', [AcademicYearController::class, 'restore']);
    Route::apiResources([
      'academic-years/holidays' => HolidayController::class,
      'academic-years' => AcademicYearController::class,
      'academic-years/{academicYear}/unit-levels' => AcademicYearUnitLevelController::class
    ]);
    Route::post('/academic-years/{academicYear}/open/{openItem}', [AcademicYearArchivesController::class, 'open']);
    Route::post('/academic-years/{academicYear}/close/{closeItem}', [AcademicYearArchivesController::class, 'close']);
  });

});

