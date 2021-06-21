<?php

use Illuminate\Support\Facades\Route;
use Okotieno\LMS\Controllers\LibraryBookAuthorController;
use Okotieno\LMS\Controllers\LibraryBookController;
use Okotieno\LMS\Controllers\LibraryBookIssueController;
use Okotieno\LMS\Controllers\LibraryBookItemController;
use Okotieno\LMS\Controllers\LibraryBookPublisherController;
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

    Route::get('library-classes', [LibraryClassificationController::class, 'index']);
    Route::get('library-books/library-book-items', [LibraryBookItemController::class,'index']);
    Route::apiResources([
      'library-books/issue' => LibraryBookIssueController::class,
      'library-books/{libraryBook}/library-book-items' => LibraryBookItemController::class,
      'library-books' => LibraryBookController::class

    ]);
  });
});
