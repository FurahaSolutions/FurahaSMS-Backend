<?php

use Illuminate\Support\Facades\Route;
use Okotieno\Guardians\Controllers\GuardiansController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::get('api/guardians/{user}', [GuardiansController::class, 'show']);
  Route::patch('api/guardians/{user}', [GuardiansController::class, 'update']);
  Route::prefix('api/students/{studentUser}')->group(function () {
    Route::apiResource('guardians' ,GuardiansController::class)
      ->parameters(['guardian' => 'user']);
  });
});
