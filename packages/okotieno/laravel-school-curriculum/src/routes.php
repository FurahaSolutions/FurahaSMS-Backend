<?php

use Illuminate\Support\Facades\Route;
use Okotieno\SchoolCurriculum\Controllers\ClassLevelCategoryController;
use Okotieno\SchoolCurriculum\Controllers\ClassLevelController;
use Okotieno\SchoolCurriculum\Controllers\ClassLevelUnitLevelsController;
use Okotieno\SchoolCurriculum\Controllers\SemesterController;
use Okotieno\SchoolCurriculum\Controllers\UnitCategoryController;
use Okotieno\SchoolCurriculum\Controllers\UnitController;
use Okotieno\SchoolCurriculum\Controllers\UnitLevelController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api/curriculum')->group(function () {

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
