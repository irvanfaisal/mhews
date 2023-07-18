<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationRegenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_regencies', function (Blueprint $table) {
            $table->id();
            $table->integer('regency_id')->unsigned()->unique();
            $table->integer('province_id')->unsigned();
            $table->string('name');
            $table->float('lat');
            $table->float('lon');
            $table->timestamps();

            $table->foreign('province_id')->references('province_id')->on('location_provinces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_regencies');
    }
}
