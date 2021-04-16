<?php

use Okotieno\DataSync\Controllers\DataSyncController;

Route::middleware(['auth:api', 'bindings'])->group(function () {

  Route::apiResource('/api/data-sync', DataSyncController::class);

});
