<?php

use Illuminate\Support\Facades\Route;
use Okotieno\SchoolExams\Controllers\ExamPaperQuestionsController;
use Okotieno\SchoolExams\Controllers\ExamPapersController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api')->group(function () {
    Route::apiResources([
      'exam-papers{examPaper}/questions' => ExamPaperQuestionsController::class,
      'exam-papers' => ExamPapersController::class
    ]);
  });
});
