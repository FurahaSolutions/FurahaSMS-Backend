<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateELearningCourseContentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('e_learning_course_contents', function (Blueprint $table) {
      $table->id();
      $table->foreignId('e_learning_topic_id');
      $table->foreignId('study_material_id');
      $table->integer('position')->default(0);
      $table->softDeletes();
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
    Schema::dropIfExists('e_learning_course_contents');
  }
}
