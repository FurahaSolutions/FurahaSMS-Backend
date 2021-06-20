<?php

use Illuminate\Support\Facades\Route;
use Okotieno\SchoolAccounts\Controllers\FinancialCostsController;
use Okotieno\SchoolAccounts\Controllers\FinancialPlanController;
use Okotieno\SchoolAccounts\Controllers\PaymentMethodsController;
use Okotieno\SchoolAccounts\Controllers\StudentPaymentReceiptController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api/accounts')->group(function () {
    Route::apiResources([
      'academic-year/{academicYear}/financial-plan' => FinancialPlanController::class,
      'financial-costs' => FinancialCostsController::class,
      'payment-methods' => PaymentMethodsController::class,
    ]);
  });
  Route::prefix('api/students')->group(function () {
    Route::apiResources([
      '{user}/fee-payment-receipt' => StudentPaymentReceiptController::class
    ]);
  });
});
