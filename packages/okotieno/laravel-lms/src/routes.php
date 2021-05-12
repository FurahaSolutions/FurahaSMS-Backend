<?php

use Illuminate\Support\Facades\Route;
use Okotieno\LMS\Controllers\LibraryBookController;
use Okotieno\LMS\Controllers\Api\LibraryBookController as ApiLibraryBookController;
use Okotieno\LMS\Controllers\LibraryBookAuthorController;
use Okotieno\LMS\Controllers\LibraryBookIssueController;
use Okotieno\LMS\Controllers\LibraryBookItemController;
use Okotieno\LMS\Controllers\LibraryBookPublisherController;
use Okotieno\LMS\Controllers\LibraryBookReturnController;
use Okotieno\LMS\Controllers\LibraryBookTagController;
use Okotieno\LMS\Controllers\LibraryClassificationClassController;
use Okotieno\LMS\Controllers\LibraryClassificationController;
use Okotieno\LMS\Controllers\LibraryUserController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResource('/library-classification', LibraryClassificationController::class);
  Route::prefix('api')->group(function () {
    Route::apiResources([
      'library-books/classifications' => LibraryClassificationController::class,
      'library-books/classifications/{libraryClassification}/classes' => LibraryClassificationClassController::class,
      'library-books/tags' => LibraryBookTagController::class,
      'library-books/authors' => LibraryBookAuthorController::class,
      'library-books/publishers' => LibraryBookPublisherController::class,
      'library-books/users' => LibraryUserController::class,
    ]);

    Route::get('library-books/my-account', [ApiLibraryBookController::class, 'getMyAccount']);
    Route::get('library-classes', [ApiLibraryBookController::class, 'getClasses']);
    Route::apiResources([
      'library-books/issue' => LibraryBookIssueController::class,
      'library-books/return' => LibraryBookReturnController::class,
      'library-books/{libraryBook}/library-book-items' => LibraryBookItemController::class,
      'library-books' => LibraryBookController::class

    ]);
  });
});
