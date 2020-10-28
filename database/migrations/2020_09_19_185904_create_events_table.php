<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name', 255);
            $table->string('url', 255);
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->string('email', 191)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('twitter', 255)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->text('instagram')->nullable();
            $table->text('website')->nullable();
            $table->date('final_date')->nullable();
            $table->integer('status')->default(0)->comment('0=inactivo, 1=activo, 2=finalizado');
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
        Schema::dropIfExists('events');
    }
}
