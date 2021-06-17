<?php

use Illuminate\Support\Facades\Route;
use Okotieno\Teachers\Controllers\TeachersController;
use Okotieno\Teachers\Controllers\TeacherSubjectsController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResource('api/teachers', TeachersController::class)
    ->parameters(['teachers' => 'user']);
  Route::apiResources([
    '/api/teachers/{user}/subjects' => TeacherSubjectsController::class
  ]);
});

