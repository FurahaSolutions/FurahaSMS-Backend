<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementFulfillsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('procurement_fulfills', function (Blueprint $table) {
      $table->id();
      $table->foreignId('procurement_tender_id');
      $table->foreignId('entered_by');
      $table->boolean('fulfilled')->default(true);
      $table->string('comment');
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
    Schema::dropIfExists('procurement_fulfills');
  }
}
