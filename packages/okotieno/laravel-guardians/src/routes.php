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
  Route::prefix('api')->group(function () {
    Route::apiResource('/guardians', GuardiansController::class)
      ->parameters([
        'guardians' => 'user'
      ]);

  });
});
