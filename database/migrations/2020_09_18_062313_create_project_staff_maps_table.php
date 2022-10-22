<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectStaffMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_staff_maps', function (Blueprint $table) {
            $table->id();
			$table->integer('project_id')->nullable();
			$table->integer('staff_id')->nullable();
			$table->tinyInteger('bActive')->default(0);	
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
        Schema::dropIfExists('project_staff_maps');
    }
}
