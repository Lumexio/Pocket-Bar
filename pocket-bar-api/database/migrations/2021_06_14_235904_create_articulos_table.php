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
            $table->decimal('precio_articulo', 10, 2)->nullable('NULL');
            $table->longText('descripcion_articulo')->nullable('NULL');
            $table->foreignId('categoria_id')->nullable('NULL')->references('id')->on('categorias_tbl');
            $table->foreignId('marca_id')->nullable('NULL')->references('id')->on('marcas_tbl');
            $table->foreignId('proveedor_id')->nullable('NULL')->references('id')->on('proveedores_tbl');
            $table->foreignId('rack_id')->nullable('NULL')->references('id')->on('rack_tbl');
            $table->foreignId('tipo_id')->nullable('NULL')->references('id')->on('tipos_tbl');
            $table->foreignId('travesano_id')->nullable('NULL')->references('id')->on('travesano_tbl');
            $table->foreignId('status_id')->nullable('NULL')->references('id')->on('status_tbl');
            $table->string('foto_articulo')->nullable('NULL');
            // $table->foreignId('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
