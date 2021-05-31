<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_date_id');
            $table->foreign('event_date_id')->references('id')->on('event_dates');
            $table->string('name', 255);
            $table->time('initial_hour');
            $table->time('final_hour');
            $table->integer('quantity');
            $table->integer('used')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turns');
    }
}
