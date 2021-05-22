<?php

use Illuminate\Support\Facades\Route;
use Okotieno\Teachers\Controllers\TeachersController;
use Okotieno\Teachers\Controllers\TeacherSubjectsController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResources([
    '/api/teachers' => TeachersController::class,
    '/api/teachers/{user}/subjects' => TeacherSubjectsController::class
  ]);
});

