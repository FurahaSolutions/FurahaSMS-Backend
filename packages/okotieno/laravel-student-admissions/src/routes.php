<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 9/5/2019
 * Time: 11:15 PM
 */

use Illuminate\Support\Facades\Route;
use Okotieno\StudentAdmissions\Controllers\StudentIdNumberController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api')->group(function () {
    Route::prefix('admissions')->group(function () {
      Route::prefix('students')->group(function () {
        Route::apiResources([
          'guardians' => \Okotieno\Guardians\Controllers\StudentGuardiansController::class
        ]);
      });
    });
    Route::prefix('student')->group(function () {
      Route::prefix('id-number')->group(function () {
        Route::get('/', [StudentIdNumberController::class, 'get']);
        Route::get('/identification-details', [StudentIdNumberController::class, 'getIdentificationDetails']);
      });
    });
  });
});
