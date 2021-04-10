<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibraryBookTagsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('library_book_tags', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->boolean('active')->default(true);
      //$table->timestamps();
    });

    Schema::create('library_book_library_book_tag', function (Blueprint $table) {
      $table->foreignId('library_book_id');
      $table->foreignId('library_book_tag_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('library_book_library_book_tag');
    Schema::dropIfExists('library_book_tags');
  }
}
