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
    public function testArticuloList(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/api/articulo/list');
        $response->assertStatus(200);
    }


}
