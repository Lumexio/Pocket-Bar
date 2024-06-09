<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable('NULL');
            $table->foreignId('category_id')->nullable('NULL')->references('id')->on('categories');
            $table->foreignId('brand_id')->nullable('NULL')->references('id')->on('brands');
            $table->foreignId('provider_id')->nullable('NULL')->references('id')->on('providers');
            $table->foreignId('type_id')->nullable('NULL')->references('id')->on('types');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('photo_id')->nullable('NULL')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
