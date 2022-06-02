<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('present', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('present_status');
            $table->string('present_asset', 2555)->nullable();
            $table->string('present_desc', 2555)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('day_id');
            
            $table->foreign('user_id', 'present_ibfk_1')->references('id')->on('users');
            $table->foreign('day_id', 'present_ibfk_2')->references('id')->on('day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('present');
    }
}
