<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialCostItemsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('financial_cost_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('financial_cost_id');
      $table->boolean('active')->default(true);
      $table->string('name');
      $table->softDeletes();
//            $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('financial_cost_items');
  }
}
