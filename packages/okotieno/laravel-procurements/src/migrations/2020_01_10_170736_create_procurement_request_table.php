<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementRequestTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('procurement_requests', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->string('name');
      $table->foreignId('procurement_items_category_id');
      $table->string('quantity_description');
      $table->string('description');
      $table->foreignId('requested_by');
      $table->foreignId('procurement_request_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('procurement_requests');
  }
}
