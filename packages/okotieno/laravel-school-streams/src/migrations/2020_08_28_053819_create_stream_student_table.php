<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stream_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stream_id');
            $table->foreignId('student_id');
            $table->foreignId('academic_year_id');
            $table->foreignId('class_level_id');
            $table->timestamps();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->foreign('class_level_id')->references('id')->on('class_levels');
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('stream_id')->references('id')->on('streams');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stream_student');
    }
}
