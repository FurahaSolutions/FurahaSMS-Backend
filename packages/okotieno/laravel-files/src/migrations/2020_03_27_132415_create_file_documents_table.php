<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_documents', function (Blueprint $table) {
          $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('extension');
            $table->string('file_path')->unique();
            $table->string('mme_type');
            $table->string('size');
            $table->foreignId('uploaded_by');
            $table->foreign('uploaded_by')->on('users')->references('id');
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
        Schema::dropIfExists('file_documents');
    }
}
