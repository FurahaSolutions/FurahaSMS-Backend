<?php

use Okotieno\SchoolCurriculum\Controllers\ClassLevelCategoryController;
use Okotieno\SchoolCurriculum\Controllers\ClassLevelController;
use Okotieno\SchoolCurriculum\Controllers\ClassLevelUnitLevelsController;
use Okotieno\SchoolCurriculum\Controllers\SchoolCurriculumApiController;
use Okotieno\SchoolCurriculum\Controllers\SemesterController;
use Okotieno\SchoolCurriculum\Controllers\UnitApiController;
use Okotieno\SchoolCurriculum\Controllers\UnitCategoryController;
use Okotieno\SchoolCurriculum\Controllers\UnitController;
use Okotieno\SchoolCurriculum\Controllers\UnitLevelController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api/curriculum')->group(function () {
    Route::prefix('units')->group(function () {
      Route::get('/all', [UnitApiController::class, 'getAll']);
    });
    Route::prefix('unit-categories')->group(function () {
      // Route::get('/', SchoolCurriculumApiController@get');
      Route::get('/all', [SchoolCurriculumApiController::class, 'getAll']);
    });

    Route::apiResources(['unit-levels' => UnitLevelController::class,
      'units' => UnitController::class,
      'unit-categories' => UnitCategoryController::class,
      'class-levels/unit-levels' => ClassLevelUnitLevelsController::class,
      'class-levels' => ClassLevelController::class,
      'class-level-categories' => ClassLevelCategoryController::class,
      'semesters' => SemesterController::class
    ]);

  });
});
