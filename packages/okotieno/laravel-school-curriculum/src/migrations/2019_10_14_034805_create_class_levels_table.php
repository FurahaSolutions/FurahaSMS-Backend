<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassLevelsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('class_levels', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('class_level_category_id');
      $table->boolean('active');
      $table->longText('description')->nullable();
      $table->string('name');
      $table->string('abbreviation');
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('class_levels');
  }
}
