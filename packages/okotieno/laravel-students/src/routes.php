<?php

use Okotieno\Students\Controllers\StudentAcademicsController;
use Okotieno\Students\Controllers\StudentFeeStatementController;
use Okotieno\Students\Controllers\StudentGuardiansController;
use Okotieno\Students\Controllers\StudentsApiController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::get('/api/students', [StudentsApiController::class, 'get']);
  Route::prefix('api/students/{user}')->group(function () {
    Route::apiResources([
      'guardians' => StudentGuardiansController::class,
      'academics' => StudentAcademicsController::class,
      'fee-statement' => StudentFeeStatementController::class
    ]);
  });
});

