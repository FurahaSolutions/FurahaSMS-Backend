<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeTableTimingTemplatesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('time_table_timings', function (Blueprint $table) {
      $table->id();
      $table->time('start');
      $table->time('end');
      $table->timestamps();
    });
    Schema::create('time_table_timing_templates', function (Blueprint $table) {
      $table->id();
      $table->string('description');
      $table->timestamps();
    });

    Schema::create('time_table_timing_time_table_timing_template', function (Blueprint $table) {
      $table->foreignId('time_table_timing_id');
      $table->foreignId('time_table_timing_template_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('time_table_timings');
    Schema::dropIfExists('time_table_timing_templates');
    Schema::dropIfExists('time_table_timing_time_table_timing_template');
  }
}
