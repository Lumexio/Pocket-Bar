<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_tbl', function (Blueprint $table) {
            $table->id();
            $table->decimal("total", 10, 2)->nullable(false);
            $table->decimal("subtotal", 10, 2)->nullable(false);
            $table->integer("item_count")->nullable(false);
            $table->string("user_name");
            $table->dateTime("ticket_date");
            $table->foreignId("user_id")->nullable(false)->references("id")->on("users");
            $table->decimal("tax", 10, 2);
            $table->decimal("discounts", 10, 2);
            $table->decimal("tip", 10, 2);
            $table->decimal("min_tip", 10, 2);
            $table->string("table_name");
            $table->foreignId("table_id")->nullable(false)->references("id")->on("tables");
            $table->string("status")->default("Solicitado");
            $table->boolean("closed")->default(false);
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
