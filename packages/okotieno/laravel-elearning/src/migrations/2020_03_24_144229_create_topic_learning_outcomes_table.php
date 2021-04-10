<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicLearningOutcomesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('topic_learning_outcomes', function (Blueprint $table) {
      $table->id();
      $table->string('description');
      $table->foreignId('e_learning_topic_id');
      $table->integer('position')->default(0);
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
    Schema::dropIfExists('topic_learning_outcomes');
  }
}
