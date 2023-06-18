<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TipoTest extends TestCase
{
    /*
     * Refresh the database after each test.
     */
    use RefreshDatabase;
    public bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_tipo_index()
    {
        $user = User::where("rol_id", "=", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/tipo');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'tipos' => [
                '*' => [
                    'id',
                    'nombre_tipo',
                    "descripcion_tipo",
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    public function test_tipo_store()
    {
        $user = User::where("rol_id", "=", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/tipo', [
            'nombre_tipo' => 'test',
            'descripcion_tipo' => 'test'
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'tipo' => [
                'id',
                'nombre_tipo',
                "descripcion_tipo",
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function test_tipo_show()
    {
        $user = User::where("rol_id", "=", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/tipo/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'tipo' => [
                'id',
                'nombre_tipo',
                "descripcion_tipo",
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function test_tipo_update()
    {
        $user = User::where("rol_id", "=", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/tipo/1', [
            'nombre_tipo' => 'test',
            'descripcion_tipo' => 'test'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'tipo' => [
                'id',
                'nombre_tipo',
                "descripcion_tipo",
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function test_tipo_active()
    {
        $user = User::where("rol_id", "=", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/tipo/activate/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'tipo'
        ]);
    }
}
