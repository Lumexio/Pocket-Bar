<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_details', function (Blueprint $table) {
            $table->id();
            $table->integer("units")->nullable(false);
            $table->decimal("discounts", 10, 2);
            $table->decimal("tax", 10, 2);
            $table->decimal("subtotal", 10, 2)->nullable(false);
            $table->decimal("total", 10, 2)->nullable(false);
            $table->foreignId("product_id")->nullable(false)->references("id")->on("products");
            $table->string("status")->default("En espera");
            $table->foreignId("barTender_id")->nullable(true)->references("id")->on("users");
            $table->foreignId("waiter_id")->nullable(false)->references("id")->on("users");
            $table->foreignId("ticket_id")->nullable(false)->references('id')->on('tickets');
            $table->softDeletes();
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
        Schema::dropIfExists('ticket_details');
    }
}
