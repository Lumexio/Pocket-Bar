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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string("type")->nullable(false);
            $table->decimal("tip", 10, 2)->nullable(false);
            $table->decimal("total", 10, 2)->nullable(false);
            $table->decimal("diff", 10, 2)->nullable('NULL');
            $table->decimal("nominas_paid", 10, 2)->nullable('NULL');
            $table->json("vouchers")->nullable('NULL');
            $table->json("nominas")->nullable('NULL');
            $table->foreignId("ticket_id")->nullable(false)->references('id')->on('tickets');
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
        Schema::dropIfExists("payments");
    }
}
