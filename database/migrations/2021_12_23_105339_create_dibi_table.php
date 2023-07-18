<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDibiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dibi', function (Blueprint $table) {
            $table->id();
            $table->integer('regency_id')->unsigned();
            $table->index('regency_id');
            $table->string('regency_name')->nullable();
            $table->string('province_name')->nullable();
            $table->date('date')->index();
            $table->string('hazard');
            $table->text('location')->nullable();
            $table->text('chronology')->nullable();
            $table->text('cause')->nullable();
            $table->integer('dead')->nullable();
            $table->integer('missing')->nullable();
            $table->integer('injured')->nullable();
            $table->integer('house')->nullable();
            $table->integer('facility')->nullable();
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
        Schema::dropIfExists('dibi');
    }
}
