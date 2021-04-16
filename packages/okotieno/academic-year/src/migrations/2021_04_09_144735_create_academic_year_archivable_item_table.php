<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearArchivableItemTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('academic_year_archivable_item', function (Blueprint $table) {
      $table->id();
      $table->foreignId('archivable_item_id');
      $table->foreignId('academic_year_id');
      $table->foreign('academic_year_id')->references('id')->on('academic_years');
      $table->foreign('archivable_item_id')->references('id')->on('archivable_items');
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
    Schema::dropIfExists('academic_year_archivable_item');
  }
}
