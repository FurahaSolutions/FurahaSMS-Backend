<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamPaperQuestionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('exam_paper_questions', function (Blueprint $table) {
      $table->id();
      $table->softDeletes();
      $table->longText('description');
      $table->longText('correct_answer_description');
      $table->boolean('multiple_answers');
      $table->boolean('multiple_choices');
      $table->double('exam_paper_id');
      $table->integer('points');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('exam_paper_questions');
  }
}
