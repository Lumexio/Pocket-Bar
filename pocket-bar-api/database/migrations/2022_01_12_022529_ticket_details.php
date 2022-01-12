<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TicketDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_details_tbl', function (Blueprint $table) {
            $table->id();
            $table->integer("units")->nullable(false);
            $table->decimal("unit_price", 10, 2)->nullable(false);
            $table->decimal("total", 10, 2)->nullable(false);
            $table->decimal("subtotal", 10, 2)->nullable(false);
            $table->foreignId("articulos_tbl_id")->nullable(false)->references('id')->on('articulos_tbl');
            $table->string("articulos_img", 300);
            $table->boolean("attended")->default(true);
            $table->foreignId("ticket_id")->nullable(false)->references('id')->on('tickets_tbl');
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
        //
    }
}
