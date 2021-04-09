<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeTablesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('time_tables', function (Blueprint $table) {
      $table->id();
      $table->softDeletes();
      $table->foreignId('time_table_timing_template_id');
      $table->string('description');
      $table->foreignId('academic_year_id');
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
    Schema::dropIfExists('time_tables');
  }
}
