<?php

use Illuminate\Support\Facades\Route;
use Okotieno\SchoolInfrastructure\Controllers\RoomsController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResource('/api/school-rooms', RoomsController::class);
});
