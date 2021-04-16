<?php

use Okotieno\Procurement\Controllers\ProcurementItemsCategoryController;
use Okotieno\Procurement\Controllers\ProcurementRequestApiController;
use Okotieno\Procurement\Controllers\ProcurementRequestApprovalController;
use Okotieno\Procurement\Controllers\ProcurementRequestController;
use Okotieno\Procurement\Controllers\ProcurementRequestTenderController;
use Okotieno\Procurement\Controllers\ProcurementTenderBidsController;
use Okotieno\Procurement\Controllers\ProcurementTenderController;
use Okotieno\Procurement\Controllers\ProcurementTenderFulfillmentController;
use Okotieno\Procurement\Controllers\ProcurementVendorsController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api/procurements')->group(function () {
    Route::apiResources([
      'tenders/{procurementTender}/fulfilled' => ProcurementTenderFulfillmentController::class,
      'tenders/{procurementTender}/bids' => ProcurementTenderBidsController::class,
      'tenders' => ProcurementTenderController::class,
      'vendors' => ProcurementVendorsController::class,
      'requests/pending-tendering' => ProcurementRequestTenderController::class,
      'requests/pending-approval' => ProcurementRequestApprovalController::class,
      'requests' => ProcurementRequestController::class,
      'item-categories' => ProcurementItemsCategoryController::class,
    ]);
    Route::get('my-requests', [ProcurementRequestApiController::class, 'myRequests']);
  });
});
