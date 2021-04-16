<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGendersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('genders', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('abbreviation');
      $table->boolean('active')->default(true);
    });
    DB::table('genders')->insert([
      ['name' => 'Male', 'abbreviation' => 'M'],
      ['name' => 'Female', 'abbreviation' => 'F'],
    ]);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('genders');
  }
}
