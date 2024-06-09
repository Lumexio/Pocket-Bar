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
            $table->id();
            $table->foreignId('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade')->onUpdate("cascade");
            $table->foreignId('branch_id')->references('id')->on('branches')->onDelete('cascade')->onUpdate("cascade");
            $table->integer('stock')->default(0)->nullable('NULL')->comment('Stock actual del producto en unidades');
            $table->integer('stock_eq')->default(0)->nullable('NULL')->comment('Stock equivalente en la unidad de medida base del producto. Ej: Si 1 Coca Cola = 500 ml. Y hay 10 coca colas, el stock_eq es 5000 ml.');
            $table->integer('minimum_stock')->default(0)->nullable('NULL')->comment('Stock minimo permitido. Si el stock llega a este valor se debe notificar al usuario');
            $table->date("deactivated_at")->nullable("NULL");
            $table->unique(['product_id', 'branch_id']);
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
        Schema::dropIfExists('stocks');
    }
}
