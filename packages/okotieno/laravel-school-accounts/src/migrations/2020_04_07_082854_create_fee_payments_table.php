<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeePaymentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('fee_payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('student_id');
      $table->double('amount');
      $table->foreignId('payment_method_id');
      $table->string('ref')->nullable();
      $table->date('transaction_date');
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
    Schema::dropIfExists('fee_payments');
  }
}
