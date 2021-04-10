<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyMaterialsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('study_materials', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->boolean('public')->default(true);
      $table->foreignId('study_material_doc_id');
      $table->string('title');
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
    Schema::dropIfExists('study_materials');
  }
}
