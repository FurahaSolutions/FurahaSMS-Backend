<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherUnitLevelTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('teacher_unit_level', function (Blueprint $table) {
      $table->id();
      $table->foreignId('teacher_id');
      $table->foreignId('unit_level_id');
      $table->timestamps();
      $table->foreign('teacher_id')->references('id')->on('teachers');
      $table->foreign('unit_level_id')->references('id')->on('unit_levels');

    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('teacher_unit_level');
  }
}
