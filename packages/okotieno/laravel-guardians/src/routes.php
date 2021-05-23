<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 9/5/2019
 * Time: 11:15 PM
 */

use Illuminate\Support\Facades\Route;
use Okotieno\Guardians\Controllers\GuardiansController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::get('api/guardians/{user}', [GuardiansController::class, 'show']);
  Route::prefix('api/students/{studentUser}')->group(function () {
    Route::apiResource('guardians' ,GuardiansController::class)
      ->parameters(['guardian' => 'user']);
  });
});
