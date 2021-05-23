<?php

use Illuminate\Support\Facades\Route;
use Okotieno\Students\Controllers\StudentAcademicsController;
use Okotieno\Students\Controllers\StudentFeeStatementController;
use Okotieno\Students\Controllers\StudentsController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResource('api/students', StudentsController::class)
    ->parameters(['students' => 'user']);
  Route::prefix('api/students/{user}')->group(function () {
    Route::apiResources([
      'academics' => StudentAcademicsController::class,
      'fee-statement' => StudentFeeStatementController::class
    ]);
  });
});

