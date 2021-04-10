<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementVendorContactsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('procurement_vendor_contacts', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->boolean('is_email')->default(false);
      $table->boolean('is_phone')->default(false);
      $table->string('value');
      $table->foreignId('procurement_vendor_id');
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
    Schema::dropIfExists('procurement_vendor_contacts');
  }
}
