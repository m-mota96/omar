<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessTurnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_turn', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_id');
            $table->unsignedBigInteger('turn_id');
            $table->foreign('access_id')->references('id')->on('accesses');
            $table->foreign('turn_id')->references('id')->on('turns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_turn');
    }
}
