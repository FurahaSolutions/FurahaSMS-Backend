<?php

use Illuminate\Support\Facades\Route;
use Okotieno\PermissionsAndRoles\Controllers\PermissionsRolesController;

Route::middleware(['auth:api', 'bindings'])->group(function () {

  Route::apiResource('/api/permissions-and-roles/roles', PermissionsRolesController::class);

});

