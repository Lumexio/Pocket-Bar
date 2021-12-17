<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RackTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rack_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("nombre_rack");
            //$table->foreignId('travesano_id')->nullable('NULL')->references('id')->on('travesano_tbl');
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
        Schema::dropIfExists('rack_tbl');
    }
}
