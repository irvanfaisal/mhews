<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('station_id');
            $table->float('sensor_1')->nullable();
            $table->float('sensor_2')->nullable();
            $table->float('sensor_3')->nullable();
            $table->float('residu_1')->nullable();
            $table->float('residu_2')->nullable();
            $table->float('residu_3')->nullable();
            $table->dateTime('date');
            $table->string('hash')->unique();
            $table->timestamps();

            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observations');
    }
}
