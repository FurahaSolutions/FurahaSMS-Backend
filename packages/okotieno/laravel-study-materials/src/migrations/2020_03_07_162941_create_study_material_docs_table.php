<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyMaterialDocsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('study_material_docs', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type');
      $table->string('extension');
      $table->string('file_path')->unique();
      $table->string('mme_type');
      $table->string('size');
      $table->foreignId('user_id');
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
    Schema::dropIfExists('study_material_docs');
  }
}
