<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementItemsCategoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('procurement_items_categories', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->string('name');
    });
    DB::table('procurement_items_categories')->insert([
      ['name' => 'School Uniform'],
      ['name' => 'Farm Products'],
      ['name' => 'Library Books'],
      ['name' => 'Furniture'],
      ['name' => 'Kitchen Equipments'],
      ['name' => 'Sports Equipment'],
      ['name' => 'Stationery'],
    ]);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('procurement_items_categories');
  }
}
