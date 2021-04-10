<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearUnitAllocationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('academic_year_unit_allocations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('unit_level_id');
      $table->foreignId('academic_year_id');
      $table->foreignId('class_level_id');
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
    Schema::dropIfExists('academic_year_unit_allocations');
  }
}
