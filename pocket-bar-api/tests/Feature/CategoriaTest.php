<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    use RefreshDatabase;
    public bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_categoria_list(): void
    {
        $user = User::where("name", "=", "admin")->first();
        $response = $this->actingAs($user)->get('/api/categoria/');
        $response->assertStatus(200);
    }

    public function test_categoria_create(): void
    {
        $user = User::where("name", "=", "admin")->first();
        $response = $this->actingAs($user)->post('/api/categoria/', [
            "nombre_categoria" => "categoria 1",
            "descripcion" => "descripcion de la categoria 1",
            "active" => true
        ], [
            "Accept" => "application/json",
            "Content-Type" => "application/json"
        ]);
        $response->assertStatus(201);
    }
}
