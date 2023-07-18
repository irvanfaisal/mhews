<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationVillagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_villages', function (Blueprint $table) {
            $table->id();
            $table->integer('village_id')->unsigned();
            $table->index('village_id');
            $table->integer('subdistrict_id')->unsigned();
            $table->string('name');
            $table->float('lat');
            $table->float('lon');
            $table->timestamps();

            $table->foreign('subdistrict_id')->references('subdistrict_id')->on('location_subdistricts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_villages');
    }
}
