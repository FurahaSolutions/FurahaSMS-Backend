<?php


use Okotieno\LMS\Controllers\Api\LibraryBookAuthorController as ApiLibraryBookAuthorController;
use Okotieno\LMS\Controllers\Api\LibraryBookController;
use Okotieno\LMS\Controllers\Api\LibraryBookController as ApiLibraryBookController;
use Okotieno\LMS\Controllers\Api\LibraryBookPublisherController as ApiLibraryBookPublisherController;
use Okotieno\LMS\Controllers\LibraryBookAuthorController;
use Okotieno\LMS\Controllers\LibraryBookIssueController;
use Okotieno\LMS\Controllers\LibraryBookItemController;
use Okotieno\LMS\Controllers\LibraryBookPublisherController;
use Okotieno\LMS\Controllers\LibraryBookReturnController;
use Okotieno\LMS\Controllers\LibraryBookTagController;
use Okotieno\LMS\Controllers\LibraryBookTagController as ApiLibraryBookTagController;
use Okotieno\LMS\Controllers\LibraryClassificationClassController;
use Okotieno\LMS\Controllers\LibraryClassificationController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::apiResource('/library-classification', LibraryClassificationController::class);
  Route::prefix('api')->group(function () {
    Route::apiResources([
      'library-books/classifications' => LibraryClassificationController::class,
      'library-books/classifications/{libraryClassification}/classes' => LibraryClassificationClassController::class,
      'library-book-tag' => LibraryBookTagController::class,
      'library-book-author' => LibraryBookAuthorController::class,
      'library-book-publisher' => LibraryBookPublisherController::class
    ]);

    Route::get('library-books/filter', [LibraryBookController::class, 'filter']);
    Route::get('api/library-books/tags/all', [ApiLibraryBookTagController::class, 'all']);
    Route::get('api/library-books/tags/filter', [ApiLibraryBookTagController::class . 'filter']);
    Route::get('library-books/all', [ApiLibraryBookController::class, 'getAll']);
    Route::get('library-books/issued/all', [ApiLibraryBookController::class, 'getAllIssuedBooks']);
    Route::get('library-books/authors/all', [ApiLibraryBookAuthorController::class, 'all']);
    Route::get('library-books/authors/filter', [ApiLibraryBookAuthorController::class, 'filter']);
    Route::get('library-books/publishers/all', [ApiLibraryBookPublisherController::class, 'all']);
    Route::get('library-books/publishers/filter', [ApiLibraryBookPublisherController::class, 'filter']);
    Route::get('library-books/my-account', [ApiLibraryBookController::class, 'getMyAccount']);
    Route::get('library-classes', [ApiLibraryBookController::class, 'getClasses']);
    Route::apiResources([
      'library-books' => LibraryBookController::class,
      'library-book/issue' => LibraryBookIssueController::class,
      'library-book/return' => LibraryBookReturnController::class,
      'library-book/{libraryBook}/library-book-items' => LibraryBookItemController::class
    ]);
  });
});
