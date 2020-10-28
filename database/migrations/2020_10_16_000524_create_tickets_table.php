<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->integer('price')->nullable();
            $table->integer('quantity');
            $table->integer('sales')->default(0);
            $table->date('start_sale');
            $table->date('stop_sale');
            $table->integer('min_reservation')->default(1);
            $table->integer('max_reservation')->default(10);
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
