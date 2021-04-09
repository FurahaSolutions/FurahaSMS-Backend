<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Okotieno\PermissionsAndRoles\Models\Permission;

class CreateArchivableItemsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('archivable_items', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->string('name');
      $table->string('slug');
      $table->foreignId('permission_id');
      $table->foreign('permission_id')->references('id')->on('permissions');
    });

    DB::table('archivable_items')->insert([
      [
        'slug' => 'admissions',
        'name' => 'Student Admissions',
        'permission_id' => Permission::create(['name' => 'close academic year admissions'])->id
      ],
      [
        'slug' => 'subject-creation',
        'name' => 'Subject Creation',
        'permission_id' => Permission::create(['name' => 'close academic year subject creation'])->id
      ],

      [
        'slug' => 'financial-plan',
        'name' => 'Financial Plan',
        'permission_id' => Permission::create(['name' => 'close academic year financial plan'])->id
      ],
      [
        'slug' => 'score-amendment',
        'name' => 'Score Amendment',
        'permission_id' => Permission::create(['name' => 'close academic year score amendment'])->id
      ],
      [
        'slug' => 'timetable-amendment',
        'name' => 'Timetable Amendment',
        'permission_id' => Permission::create(['name' => 'close academic year timetable amendment'])->id
      ],
    ]);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('archivable_items');
  }
}
