<?php

use Okotieno\SchoolExams\Controllers\ExamPaperQuestionsController;
use Okotieno\SchoolExams\Controllers\ExamPapersController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api/academics/exam-papers')->group(function () {
    Route::apiResources([
      '{examPaper}/questions' => ExamPaperQuestionsController::class,
      '' => ExamPapersController::class
    ]);
  });
});
