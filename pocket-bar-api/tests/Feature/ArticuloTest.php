<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticuloTest extends TestCase
{
    /*
     * Refresh the database after each test
     */
    use RefreshDatabase;
    public bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_articulo_create()
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/articulo/create', [
            'nombre_articulo' => 'test',
            'cantidad_articulo' => 1,
            'descripcion_articulo' => 'test',
            'foto_articulo' => null,
            'categoria_id' => 1,
            'marca_id' => 1,
            'proveedor_id' => 1,
            'tipo_id' => 1,
            'status_id' => 1,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                "message",
                "articulo" => [
                    "id",
                    "nombre_articulo",
                    "cantidad_articulo",
                    "descripcion_articulo",
                    "foto_articulo",
                    "categoria_id",
                    "marca_id",
                    "proveedor_id",
                    "tipo_id",
                    "status_id",
                    "user_id",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_articulo_create_unauthorized()
    {
        $response = $this->post('/api/articulo/create', [
            'nombre_articulo' => 'test',
            'cantidad_articulo' => 1,
            'descripcion_articulo' => 'test',
            'foto_articulo' => null,
            'categoria_id' => 1,
            'marca_id' => 1,
            'proveedor_id' => 1,
            'tipo_id' => 1,
            'status_id' => 1,
        ]);
        $response->assertStatus(302);

        $user = User::where("rol_id", "=", 3)->first();
        $response = $this->actingAs($user)->post('/api/articulo/create', [
            'nombre_articulo' => 'test',
            'cantidad_articulo' => 1,
            'descripcion_articulo' => 'test',
            'foto_articulo' => null,
            'categoria_id' => 1,
            'marca_id' => 1,
            'proveedor_id' => 1,
            'tipo_id' => 1,
            'status_id' => 1,
        ]);
        $response->assertStatus(403);
    }

    public function test_articulo_list()
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/articulo/list');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "articulos" => [
                    [
                        "id",
                        "nombre_articulo",
                        "cantidad_articulo",
                        "precio_articulo",
                        "descripcion_articulo",
                        "foto_articulo",
                        "nombre_categoria",
                        "nombre_marca",
                        "nombre_proveedor",
                        "nombre_status",
                        "nombre_tipo",
                        "deactivated_at"
                    ]
                ]
            ]
        );
    }

    public function test_articulo_list_unauthorized()
    {
        $response = $this->get('/api/articulo/list');
        $response->assertStatus(302);
    }

    public function test_articulo_deactivate()
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/articulo/activate/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "articulo" => [
                    "id",
                    "nombre_articulo",
                    "cantidad_articulo",
                    "precio_articulo",
                    "descripcion_articulo",
                    "foto_articulo",
                    "categoria_id",
                    "marca_id",
                    "proveedor_id",
                    "tipo_id",
                    "status_id",
                    "foto_articulo",
                    "user_id",
                    "updated_at",
                    "created_at",
                    "deactivated_at"
                ]
            ]
        );
    }

    public function test_articulo_update()
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/articulo/update/1', [
            'nombre_articulo' => 'test',
            'cantidad_articulo' => 1,
            'descripcion_articulo' => 'test',
            'foto_articulo' => null,
            'categoria_id' => 1,
            'marca_id' => 1,
            'proveedor_id' => 1,
            'tipo_id' => 1,
            'status_id' => 1,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "articulo" => [
                    "id",
                    "nombre_articulo",
                    "cantidad_articulo",
                    "precio_articulo",
                    "descripcion_articulo",
                    "foto_articulo",
                    "categoria_id",
                    "marca_id",
                    "proveedor_id",
                    "tipo_id",
                    "status_id",
                    "foto_articulo",
                    "user_id",
                    "updated_at",
                    "created_at",
                    "deactivated_at"
                ]
            ]
        );
    }
}
