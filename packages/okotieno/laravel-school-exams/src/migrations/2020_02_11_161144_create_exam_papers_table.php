<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamPapersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('exam_papers', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->foreignId('unit_level_id');
      $table->boolean('available')->default(false);
      $table->boolean('private')->default(false);
      $table->boolean('access_by_key')->default(false);
      $table->foreignId('created_by');
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
    Schema::dropIfExists('exam_papers');
  }
}
