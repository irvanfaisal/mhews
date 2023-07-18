<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherForecastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_forecasts', function (Blueprint $table) {
            $table->id();
            $table->integer('regency_id')->unsigned();
            $table->string('forecast_time')->index();
            $table->datetime('date')->index();
            $table->float('rain');
            $table->float('temperature');
            $table->float('rh');
            $table->float('radiation');
            $table->float('pressure');
            $table->float('wspd');
            $table->float('wdir');
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
        Schema::dropIfExists('weather_forecasts');
    }
}
