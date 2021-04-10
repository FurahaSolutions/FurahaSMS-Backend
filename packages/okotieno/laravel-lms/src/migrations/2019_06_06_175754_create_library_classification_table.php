<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibraryClassificationTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('library_classifications', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('abbr');
      // $table->timestamps();
    });

    DB::table('library_classifications')->insert([
      ['name' => 'Dewey Decimal Classification', 'abbr' => 'DDC'],
      ['name' => 'Library of Congress Classification', 'abbr' => 'LCC'],
      ['name' => 'Colon classification', 'abbr' => 'CC'],
      ['name' => 'Universal Decimal Classification ', 'abbr' => 'UDC'],
    ]);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('library_classifications');
  }
}
