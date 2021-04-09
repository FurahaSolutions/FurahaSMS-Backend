<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicNumberStylesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('topic_number_styles', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->boolean('default')->default(false);
      $table->boolean('active')->default(true);
      $table->softDeletes();
//            $table->timestamps();
    });
    DB::table('topic_number_styles')->insert([
      [
        'name' => 'Chapter',
        'default' => true
      ],
      [
        'name' => 'Topic',
        'default' => false
      ],
      [
        'name' => 'Module',
        'default' => false
      ],
      [
        'name' => 'Lesson',
        'default' => false
      ]
    ]);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('topic_number_styles');
  }
}
