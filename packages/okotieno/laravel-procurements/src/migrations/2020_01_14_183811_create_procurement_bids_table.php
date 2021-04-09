<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementBidsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('procurement_bids', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tender_id');
      $table->foreignId('vendor_id');
      $table->string('unit_description');
      $table->string('description')->nullable();
      $table->double('price_per_unit');
      $table->boolean('awarded')->nullable();
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
    Schema::dropIfExists('procurement_bids');
  }
}
