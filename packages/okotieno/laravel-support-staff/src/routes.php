<?php

use Illuminate\Support\Facades\Route;
use Okotieno\SupportStaff\Controllers\SupportStaffController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
    Route::apiResource('/api/support-staffs', SupportStaffController::class)
      ->parameters(['support_staff' => 'user']);
});

