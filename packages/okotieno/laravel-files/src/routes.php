<?php

use Okotieno\Files\Controllers\FilesController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResource('/api/files', FilesController::class);
});

