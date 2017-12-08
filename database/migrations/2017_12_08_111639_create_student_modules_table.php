<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->integer('module_id')->unsigned();
            $table->foreign('module_id')->references('id')->on('modules');
            $table->integer('mark');
            $table->boolean('done');
            $table->string('approved_by');
            $table->foreign('approved_by')->references('id')->on('users');
            $table->date('begin_date');
            $table->date('finish_date');
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
        Schema::dropIfExists('student_modules');
    }
}
