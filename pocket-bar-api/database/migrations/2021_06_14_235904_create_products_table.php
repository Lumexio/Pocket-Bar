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
            $table->decimal('price', 10, 2)->nullable('NULL');
            $table->longText('description')->nullable('NULL');
            $table->foreignId('category_id')->nullable('NULL')->references('id')->on('categories');
            $table->foreignId('brand_id')->nullable('NULL')->references('id')->on('brands');
            $table->foreignId('provider_id')->nullable('NULL')->references('id')->on('providers');
            $table->foreignId('type_id')->nullable('NULL')->references('id')->on('types');
            $table->foreignId('status_id')->nullable('NULL')->references('id')->on('statuses');
            $table->string('image')->nullable('NULL');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
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
