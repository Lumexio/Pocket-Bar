<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NominasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominas', function (Blueprint $table) {
            $table->id();
            $table->string("user_name")->nullable(false);
            $table->foreignId("user_id")->references("id")->on("users")->nullable(false);
            $table->decimal("base", 10, 2)->nullable(false);
            $table->decimal("paid", 10, 2)->nullable(false);
            $table->foreignId("workshift_id")->nullable(false)->references("id")->on("workshifts");
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
        Schema::dropIfExists("nominas");
    }
}
