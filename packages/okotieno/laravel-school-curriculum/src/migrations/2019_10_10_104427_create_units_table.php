<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('units', function (Blueprint $table) {
      $table->id();
      $table->foreignId('unit_category_id');
      $table->boolean('active')->default(true);
      $table->boolean('default')->default(true);
      $table->string('name');
      $table->string('abbreviation');
      $table->longText('description')->nullable();
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
    Schema::dropIfExists('units');
  }
}
