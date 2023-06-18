<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProveedoresTest extends TestCase
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
    public function test_proveedores_list(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/proveedor/', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "proveedores" => [
                    [
                        "id",
                        "nombre_proveedor",
                        "descripcion",
                        "active",
                        "updated_at",
                        "created_at"
                    ]
                ]
            ]
        );
    }

    public function test_proveedores_create(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/proveedor/', [
            "nombre_proveedor" => "ProveedorDePrueba",
            "descripcion" => "Descripcion de prueba",
            "active" => true
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                "message",
                "proveedor" => [
                    "id",
                    "nombre_proveedor",
                    "descripcion",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_proveedores_create_fail(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/proveedor/', [
            "nombre_proveedor" => "ProveedorDePrueba",
            "descripcion" => "Descripcion de prueba",
            "active" => true
        ], [
            "Accept" => "application/json"
        ]);
        $response = $this->actingAs($user)->post('/api/proveedor/', [
            "nombre_proveedor" => "ProveedorDePrueba",
            "descripcion" => "Descripcion de prueba",
            "active" => true
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

    public function test_proveedores_update(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/proveedor/', [
            "nombre_proveedor" => "ProveedorDePrueba",
            "descripcion" => "Descripcion de prueba",
            "active" => true
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                "message",
                "proveedor" => [
                    "id",
                    "nombre_proveedor",
                    "descripcion",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
        $proveedor = $response->json("proveedor");
        $response = $this->actingAs($user)->put('/api/proveedor/' . $proveedor["id"], [
            "nombre_proveedor" => "ProveedorDePrueba2",
            "descripcion" => "Descripcion de prueba2",
            "active" => false
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "proveedor" => [
                    "id",
                    "nombre_proveedor",
                    "descripcion",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_proveedores_update_not_found(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/proveedor/100', [
            "nombre_proveedor" => "ProveedorDePrueba",
            "descripcion" => "Descripcion de prueba",
            "active" => true
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(404);
    }

    public function test_proveedores_show(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/proveedor/1', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "proveedor" => [
                    "id",
                    "nombre_proveedor",
                    "descripcion",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_proveedores_show_not_found(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/proveedor/100', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(404);
    }

    public function test_proveedores_create_unauthorized(): void
    {
        $response = $this->post('/api/proveedor/', [
            "nombre_proveedor" => "ProveedorDePrueba",
            "descripcion" => "Descripcion de prueba",
            "active" => true
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
    }

    public function test_proveedores_update_unauthorized(): void
    {
        $response = $this->put('/api/proveedor/1', [
            "nombre_proveedor" => "ProveedorDePrueba",
            "descripcion" => "Descripcion de prueba",
            "active" => true
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
    }

    public function test_proveedores_show_unauthorized(): void
    {
        $response = $this->get('/api/proveedor/1', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
    }

    public function test_proveedores_list_unauthorized(): void
    {
        $response = $this->get('/api/proveedor/', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
    }

    public function test_proveedores_activate(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/proveedor/activate/1', [], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "proveedor" => [
                    "id",
                    "nombre_proveedor",
                    "descripcion",
                    "updated_at",
                    "created_at"
                ]
            ]
        );
    }

    public function test_proveedores_activate_not_found(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/proveedor/activate/100', [], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(404);
    }

    public function test_proveedores_activate_unauthorized(): void
    {
        $response = $this->put('/api/proveedor/activate/1', [], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
    }
}
