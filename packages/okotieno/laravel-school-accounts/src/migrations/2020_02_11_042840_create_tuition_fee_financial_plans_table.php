<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTuitionFeeFinancialPlansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tuition_fee_financial_plans', function (Blueprint $table) {
      $table->id();
      $table->double('amount')->default(0);
      $table->foreignId('class_level_id');
      $table->foreignId('unit_level_id');
      $table->foreignId('semester_id');
      $table->foreignId('academic_year_id');
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
    Schema::dropIfExists('tuition_fee_financial_plans');
  }
}
