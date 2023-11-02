<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CashRegisterCloseData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_register_close_data', function (Blueprint $table) {
            $table->id();
            $table->string("type")->nullable(false);
            $table->decimal("total_tip", 10, 2)->nullable(false);
            $table->decimal("total", 10, 2)->nullable(false);
            $table->decimal("total_with_tip", 10, 2)->nullable(false);
            $table->json("vouchers")->nullable(true);
            $table->foreignId("cashier_id")->nullable(false)->references("id")->on("users");
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
        //
    }
}
