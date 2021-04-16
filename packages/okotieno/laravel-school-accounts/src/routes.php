<?php

use Okotieno\SchoolAccounts\Controllers\FinancialCostsController;
use Okotieno\SchoolAccounts\Controllers\FinancialPlanController;
use Okotieno\SchoolAccounts\Controllers\PaymentMethodsController;
use Okotieno\SchoolAccounts\Controllers\StudentPaymentReceiptController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResources([
    '/api/accounts/academic-year/{academicYear}/financial-plan' => FinancialPlanController::class,
    '/api/accounts/financial-costs' => FinancialCostsController::class,
    '/api/accounts/payment-methods' => PaymentMethodsController::class,
    '/api/accounts/students/{user}/fee-payment-receipt' => StudentPaymentReceiptController::class
  ]);
});
