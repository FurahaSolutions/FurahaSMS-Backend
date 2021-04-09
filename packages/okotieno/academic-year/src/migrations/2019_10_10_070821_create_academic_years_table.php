<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('academic_years', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->dateTime('archived_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('academic_years');
  }
}
