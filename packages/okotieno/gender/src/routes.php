<?php

use Okotieno\Gender\Controllers\GenderController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResource('/api/genders', GenderController::class);
});
