<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RolTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/api/rol');
        $response->assertStatus(200);
    }

    public function test_get_one(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/api/rol/1');
        $response->assertStatus(200);
    }

    public function test_update(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->put('/api/rol/2', [
            'name_rol' => 'Gerencia',
        ]);
        $response->assertStatus(200);
    }

    public function test_delete(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->delete('/api/rol/2');
        $response->assertStatus(200);
    }
}
