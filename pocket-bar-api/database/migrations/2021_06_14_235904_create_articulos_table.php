<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_articulo');
            $table->integer('cantidad_articulo');
            $table->longText('descripcion_articulo')->nullable('NULL');
            $table->foreignId('categoria_id')->nullable('NULL')->references('id')->on('categorias_tbl');
            $table->foreignId('marca_id')->nullable('NULL')->references('id')->on('marcas_tbl');
            $table->foreignId('proveedor_id')->nullable('NULL')->references('id')->on('proveedores_tbl');
            $table->foreignId('rack_id')->nullable('NULL')->references('id')->on('rack_tbl');
            $table->foreignId('tipo_id')->nullable('NULL')->references('id')->on('tipos_tbl');
            $table->foreignId('travesano_id')->nullable('NULL')->references('id')->on('travesano_tbl');
            $table->foreignId('status_id')->nullable('NULL')->references('id')->on('status_tbl');
            $table->string('foto_articulo')->nullable('NULL');
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
        Schema::dropIfExists('articulos_tbl');
    }
}
