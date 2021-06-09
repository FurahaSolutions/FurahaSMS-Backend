<?php

use Illuminate\Support\Facades\Route;
use Okotieno\ELearning\Controllers\ELearningCourseContentController;
use Okotieno\ELearning\Controllers\ELearningCourseController;
use Okotieno\ELearning\Controllers\TopicLearningOutcomeController;
use Okotieno\ELearning\Controllers\TopicNumberingController;
use Okotieno\ELearning\Controllers\TopicOnlineAssessmentController;

Route::middleware(['auth:api', 'bindings'])->group(function () {
  Route::prefix('api/e-learning')->group(function () {
    Route::apiResources([
      'courses' => ELearningCourseController::class,
      'course-content' => ELearningCourseContentController::class,
      'topic-numbering' => TopicNumberingController::class,
      'course-content/topics/{eLearningTopic:id}/learning-outcomes' => TopicLearningOutcomeController::class,
      'course-content/topics/{eLearningTopic:id}/online-assessments' => TopicOnlineAssessmentController::class
    ]);
  });
});

