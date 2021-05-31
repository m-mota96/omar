<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerifiedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifieds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->date('date');
            $table->integer('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verifieds');
    }
}
