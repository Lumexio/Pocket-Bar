<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MarcaTest extends TestCase
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
    public function test_marca_list(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/marca/');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "marcas" => [
                    [
                        "id",
                        "nombre_marca",
                        "descripcion_marca",
                        "active",
                        "updated_at",
                        "created_at"
                    ]
                ]
            ]
        );
    }

    public function test_marca_create(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/marca/', [
            "nombre_marca" => "Marca de prueba",
            "descripcion_marca" => "Descripcion de prueba",
            "active" => true
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                "message",
                "marca" => [
                    "id",
                    "nombre_marca",
                    "descripcion_marca",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_marca_update(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/marca/1', [
            "nombre_marca" => "Marca de prueba",
            "descripcion_marca" => "Descripcion de prueba",
            "active" => true
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "marca" => [
                    "id",
                    "nombre_marca",
                    "descripcion_marca",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_marca_show(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/marca/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "marca" => [
                    "id",
                    "nombre_marca",
                    "descripcion_marca",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_marca_activate(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/marca/activate/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "marca" => [
                    "id",
                    "nombre_marca",
                    "descripcion_marca",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_marca_update_unauthorized(): void
    {
        $response = $this->put('/api/marca/2', [
            "nombre_marca" => "Marca de prueba",
            "descripcion_marca" => "Descripcion de prueba",
            "active" => true
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

    public function test_marca_show_unauthorized(): void
    {
        $response = $this->get('/api/marca/2', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertJsonStructure(
            [
                "message"
            ]
        );
    }

    public function test_marca_activate_unauthorized(): void
    {
        $response = $this->put('/api/marca/activate/2', [
            "nombre_marca" => "Marca de prueba",
            "descripcion_marca" => "Descripcion de prueba",
            "active" => true
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
}
