<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineAssessmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('online_assessments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('exam_paper_id');
      $table->foreignId('e_learning_topic_id');
      $table->string('period');
      $table->dateTime('available_at');
      $table->dateTime('closing_at');
      $table->timestamps();
      $table->softDeletes();
      $table->foreign('exam_paper_id')->references('id')->on('exam_papers');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('online_assessments');
  }
}
