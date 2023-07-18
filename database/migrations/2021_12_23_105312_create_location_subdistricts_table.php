<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationSubdistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_subdistricts', function (Blueprint $table) {
            $table->id();
            $table->integer('subdistrict_id')->unsigned();
            $table->index('subdistrict_id');
            $table->integer('regency_id')->unsigned();
            $table->string('name');
            $table->float('lat');
            $table->float('lon');
            $table->timestamps();

            $table->foreign('regency_id')->references('regency_id')->on('location_regencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_subdistricts');
    }
}
