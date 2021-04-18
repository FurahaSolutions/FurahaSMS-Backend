<?php

use Okotieno\Files\Controllers\FilesController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::post('/api/files', [FilesController::class, 'store']);
  Route::get('/api/files/{file}', [FilesController::class, 'show']);
  Route::get('/api/files', [FilesController::class, 'index']);
});

