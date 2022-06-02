<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('day_s_uid');
            $table->unsignedBigInteger('partner_id');
            $table->integer('week');
            $table->integer('type');
            
            $table->foreign('partner_id', 'day_ibfk_1')->references('id')->on('partners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day');
    }
}
