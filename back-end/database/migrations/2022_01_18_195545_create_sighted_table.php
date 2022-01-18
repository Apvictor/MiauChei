<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSightedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sighted', function (Blueprint $table) {
            $table->id();
            $table->string('last_seen');
            $table->timestamp('data_sighted');
            $table->timestamps();

            $table->unsignedBigInteger('fk_user');
            $table->unsignedBigInteger('fk_pet');

            $table->foreign('fk_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('fk_pet')->references('id')->on('pets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sighted');
    }
}
