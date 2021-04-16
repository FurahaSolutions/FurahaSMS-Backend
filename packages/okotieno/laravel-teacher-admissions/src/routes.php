<?php

use Okotieno\TeacherAdmissions\Controllers\TeacherAdmissionsController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResource('/api/admissions/teachers', TeacherAdmissionsController::class);
});

