<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_id')->unique();
            $table->foreign('student_id')->references('id')->on('users');
            $table->integer('cohort_id')->unsigned();
            $table->foreign('cohort_id')->references('id')->on('cohorts');
            $table->string('first_name');
            $table->string('prefix')->nullable();
            $table->string('last_name');
            $table->date('started_on');
            $table->boolean('graduated');
            $table->softDeletes('deleted_at');
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
        Schema::dropIfExists('students');
    }
}
