<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('base_product_id')->comment('Product that contains the ingredient');
            $table->foreign('base_product_id')->references('id')->on('products');
            $table->unsignedBigInteger('ingredient_product_id')->comment('Product that is the ingredient');
            $table->foreign('ingredient_product_id')->references('id')->on('products');
            $table->decimal('quantity', 10, 2);
            $table->enum('unit', ['ML', 'OZ', 'L', 'UNIT']);
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->unique(['base_product_id', 'ingredient_product_id']);
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
        Schema::dropIfExists('ingredients');
    }
}
