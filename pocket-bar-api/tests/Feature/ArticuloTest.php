<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ArticuloTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_articulo_list(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/api/articulo/list');
        $response->assertStatus(200);
    }

    public function test_articulo_create(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/api/articulo/create', [
            'nombre_articulo' => 'test',
            'cantidad_articulo' => 1,
            'precio_articulo' => 1,
            'descripcion_articulo' => 'test',
            'foto_articulo' => null,
            'user_id' => 1,
            'categoria_id' => 1,
            'marca_id' => 1,
            'proveedor_id' => 1,
            'status_id' => 1,
            'tipo_id' => 1,
        ]);
        $response->assertStatus(200);
    }


    public function test_articulo_update(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->put('/api/articulo/update/1', [
            'nombre_articulo' => 'test',
            'cantidad_articulo' => 1,
            'precio_articulo' => 1,
            'descripcion_articulo' => 'test',
            'foto_articulo' => null,
            'user_id' => 1,
            'categoria_id' => 1,
            'marca_id' => 1,
            'proveedor_id' => 1,
            'status_id' => 1,
            'tipo_id' => 1,
        ]);
        $response->assertStatus(200);
    }

    public function test_articulo_delete(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->delete('/api/articulo/delete/1');
        $response->assertStatus(200);
    }
}
