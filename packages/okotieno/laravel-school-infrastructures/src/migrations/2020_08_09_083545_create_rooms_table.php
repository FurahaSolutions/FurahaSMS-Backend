<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('rooms', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->string('name');
      $table->string('abbreviation');
      $table->double('width');
      $table->double('length');
      $table->double('students_capacity');
      $table->boolean('is_classroom')->default(true);
      $table->softDeletes();
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
    Schema::dropIfExists('rooms');
  }
}
