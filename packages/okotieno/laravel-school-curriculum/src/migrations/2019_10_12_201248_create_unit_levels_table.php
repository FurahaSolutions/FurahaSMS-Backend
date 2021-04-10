<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitLevelsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('unit_levels', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->integer('level');
      $table->foreignId('unit_id');
      $table->softDeletes();
      // $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('unit_levels');
  }
}
