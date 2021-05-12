<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyncModelsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('sync_models', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('model_class');
      $table->string('api_link');
      $table->timestamps();
    });

    DB::table('sync_models')->insert([ // TODO move to config file
      [
        'name' => 'Students',
        'model_class' => \Okotieno\StudentAdmissions\Models\Student::class,
        'api_link' => env('COUNTY_SYNC_LINK') . '/api/students'
      ],
      [
        'name' => 'Guardians',
        'model_class' => \Okotieno\Guardians\Models\Guardian::class,
        'api_link' => env('SYNC_GUARDIAN_API') . '/api/guardians'
      ],
      [
        'name' => 'Teachers',
        'model_class' => \Okotieno\Teachers\Models\Teacher::class,
        'api_link' => env('SYNC_TEACHER_API') . '/api/teachers'
      ],
    ]);

    Schema::create('syncs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('sync_model_id');
      $table->foreignId('model_id');
      $table->boolean('synced')->default(false);
      $table->timestamp('last_synced')->nullable();

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
    Schema::dropIfExists('sync_models');
    Schema::dropIfExists('syncs');
  }
}
