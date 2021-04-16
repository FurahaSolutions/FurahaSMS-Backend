<?php

use Okotieno\PermissionsAndRoles\Controllers\PermissionsRolesController;

Route::middleware(['auth:api', 'bindings'])->group(function () {

  Route::apiResource('/api/permissions-and-roles/roles', PermissionsRolesController::class);

});

