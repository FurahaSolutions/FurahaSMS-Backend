<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookIssuesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('book_issues', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id');
      $table->foreignId('library_book_item_id');
      $table->date('issue_date');
      $table->date('due_date')->nullable();
      $table->double('charge_per_hour_rate')->nullable();
      $table->date('returned_date')->nullable();
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
    Schema::dropIfExists('book_issues');
  }
}
