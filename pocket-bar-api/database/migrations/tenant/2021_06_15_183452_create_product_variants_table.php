<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->decimal('price', 10, 2)->nullable('NULL');
            $table->string('image')->nullable('NULL');
            $table->integer('presentation');
            $table->enum('unit', ['ML', 'OZ', 'L', 'UNIT']);
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('photo_id')->nullable('NULL')->constrained()->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('product_variants');
    }
}
