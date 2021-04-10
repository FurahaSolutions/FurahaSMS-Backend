<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateELearningTopicsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('e_learning_topics', function (Blueprint $table) {
      $table->id();
      $table->string('description');
      $table->foreignId('e_learning_topic_id')->nullable();
      $table->foreignId('e_learning_course_id')->nullable();
      $table->foreignId('topic_number_style_id')->nullable();
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
    Schema::dropIfExists('e_learning_topics');
  }
}
