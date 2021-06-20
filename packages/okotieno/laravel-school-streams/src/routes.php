<?php

use Illuminate\Support\Facades\Route;
use Okotieno\SchoolStreams\Controllers\SchoolStreamsController;
use Okotieno\SchoolStreams\Controllers\StudentStreamsController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResource('/api/class-streams', SchoolStreamsController::class);
  Route::get('/api/students/{user}/streams', [StudentStreamsController::class, 'index']);
});
