<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeTableLessonsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('time_table_lessons', function (Blueprint $table) {
      $table->id();
      $table->softDeletes();
      $table->foreignId('teacher_id')->nullable();
      $table->foreignId('time_table_id');
      $table->foreignId('week_day_id');
      $table->foreignId('room_id')->nullable();
      $table->foreignId('stream_id');
      $table->foreignId('time_table_timing_id');
      $table->foreignId('unit_id');
      $table->integer('class_level_id');
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
    Schema::dropIfExists('time_table_lessons');
  }
}
