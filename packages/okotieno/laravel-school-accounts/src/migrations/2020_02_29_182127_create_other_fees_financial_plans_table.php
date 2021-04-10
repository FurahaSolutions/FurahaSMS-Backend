<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherFeesFinancialPlansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('other_fees_financial_plans', function (Blueprint $table) {
      $table->id();
      $table->double('amount')->default(0);
      $table->foreignId('class_level_id');
      $table->foreignId('financial_cost_item_id');
      $table->foreignId('semester_id');
      $table->foreignId('academic_year_id');
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
    Schema::dropIfExists('other_fees_financial_plans');
  }
}
