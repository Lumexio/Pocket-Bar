<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_tbl', function (Blueprint $table) {
            $table->id();
            $table->string("type")->nullable(false);
            $table->decimal("tip", 10, 2)->nullable(false);
            $table->decimal("total", 10, 2)->nullable(false);
            $table->decimal("diff", 10, 2)->nullable(true);
            $table->decimal("nominas_paid", 10, 2)->nullable(true);
            $table->json("vouchers")->nullable(true);
            $table->json("nominas")->nullable(false);
            $table->foreignId("ticket_id")->nullable(false)->references('id')->on('tickets_tbl');
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
