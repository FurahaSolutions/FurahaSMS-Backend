<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateELearningCoursesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('e_learning_courses', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->foreignId('class_level_id');
      $table->foreignId('unit_level_id');
      $table->foreignId('academic_year_id');
      $table->foreignId('unit_id');
      $table->foreignId('topic_number_style_id');
      $table->longText('description')->nullable();
      $table->timestamps();
      $table->softDeletes();
      $table->foreign('class_level_id')
        ->references('id')
        ->on('class_levels');
      $table->foreign('unit_level_id')
        ->references('id')
        ->on('unit_levels');
      $table->foreign('academic_year_id')
        ->references('id')
        ->on('academic_years');
      $table->foreign('unit_id')
        ->references('id')
        ->on('units');
      $table->foreign('topic_number_style_id')
        ->references('id')
        ->on('topic_number_styles');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('e_learning_courses');
  }
}
