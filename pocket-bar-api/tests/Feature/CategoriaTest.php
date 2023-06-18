<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriaTest extends TestCase
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
    public function test_categoria_list(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/categoria/');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "categorias" => [
                    [
                        "id",
                        "nombre_categoria",
                        "descripcion_categoria",
                        "active",
                        "updated_at",
                        "created_at"
                    ]
                ]
            ]
        );
    }

    public function test_categoria_create(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/categoria/', [
            "nombre_categoria" => "categoriaUnoUwU",
            "descripcion_categoria" => "descripcion de la categoria"
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                "message",
                "categoria" => [
                    "id",
                    "nombre_categoria",
                    "descripcion_categoria",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_categoria_create_unauthoraized(): void
    {
        $response = $this->post('/api/categoria/', [
            "nombre_categoria" => "categoriaUnoUwU",
            "descripcion_categoria" => "descripcion de la categoria"
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertJsonStructure(
            [
                "message"
            ]
        );
    }

    public function test_categoria_create_conflict(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/categoria/', [
            "nombre_categoria" => "categoriaUnoUwU",
            "descripcion_categoria" => "descripcion de la categoria"
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                "message",
                "categoria" => [
                    "id",
                    "nombre_categoria",
                    "descripcion_categoria",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
        $response = $this->actingAs($user)->post('/api/categoria/', [
            "nombre_categoria" => "categoriaUnoUwU",
            "descripcion_categoria" => "descripcion de la categoria"
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(409);
        $response->assertJsonStructure(
            [
                "message"
            ]
        );
    }

    public function test_categoria_update(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/categoria/', [
            "nombre_categoria" => "categoriaUnoUwU",
            "descripcion_categoria" => "descripcion de la categoria"
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                "message",
                "categoria" => [
                    "id",
                    "nombre_categoria",
                    "descripcion_categoria",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
        $response = $this->actingAs($user)->put('/api/categoria/1', [
            "nombre_categoria" => "categoriaUnoUwU",
            "descripcion_categoria" => "descripcion de la categoria"
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "categoria" => [
                    "id",
                    "nombre_categoria",
                    "descripcion_categoria",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_categoria_update_unauthoraized(): void
    {
        $response = $this->put('/api/categoria/1', [
            "nombre_categoria" => "categoriaUnoUwU",
            "descripcion_categoria" => "descripcion de la categoria"
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertJsonStructure(
            [
                "message"
            ]
        );
    }

    public function test_categoria_update_not_found(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/categoria/100', [
            "nombre_categoria" => "categoriaUnoUwU",
            "descripcion_categoria" => "descripcion de la categoria"
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(404);
        $response->assertJsonStructure(
            [
                "message"
            ]
        );
    }

    public function test_categoria_activate(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/categoria/activate/1', [], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "categoria" => [
                    "id",
                    "nombre_categoria",
                    "descripcion_categoria",
                    "updated_at",
                    "created_at",
                    "active"
                ]
            ]
        );
        $response = $this->actingAs($user)->put('/api/categoria/activate/1', [], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "categoria" => [
                    "id",
                    "nombre_categoria",
                    "descripcion_categoria",
                    "updated_at",
                    "created_at",
                    "active"
                ]
            ]
        );
    }

    public function test_categoria_activate_unauthoraized(): void
    {
        $response = $this->put('/api/categoria/activate/1', [], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertJsonStructure(
            [
                "message"
            ]
        );
    }

    public function test_categoria_activate_not_found(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/categoria/activate/100', [], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(404);
        $response->assertJsonStructure(
            [
                "message"
            ]
        );
    }
}
