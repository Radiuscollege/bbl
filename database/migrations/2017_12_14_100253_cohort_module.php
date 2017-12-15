<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CohortModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohort_module', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('cohort_id')->unsigned();
            $table->timestamps();
            
            $table->primary(['module_id', 'cohort_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cohort_module');
    }
}
