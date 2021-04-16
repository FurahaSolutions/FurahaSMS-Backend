<?php

use Okotieno\StudyMaterials\Controllers\StudyMaterialController;
use Okotieno\StudyMaterials\Controllers\StudyMaterialFilesController;

Route::middleware(['auth:api', 'bindings'])->group(function () {

  Route::apiResources(
    [
      '/api/study-materials/document-uploads' => StudyMaterialFilesController::class,
      '/api/study-materials' => StudyMaterialController::class
    ]);

});
