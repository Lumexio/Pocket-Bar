<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate("cascade");
            $table->foreignId('branch_id')->references('id')->on('branches')->onDelete('cascade')->onUpdate("cascade");
            $table->integer('units');
            $table->primary(['product_id', 'branch_id']);
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
        Schema::dropIfExists('stock');
    }
}
