<?php

use Okotieno\Religion\Controllers\ReligionController;

Route::middleware(['auth:api', 'bindings'])->group(function () {

  Route::apiResource('/api/religions', ReligionController::class);

});
