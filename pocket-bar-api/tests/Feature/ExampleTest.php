<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogIn()
    {
        $response = $this->post('/api/login', ["name"=>"admin", "password"=>"12345678"]);
        $response->assertStatus(200);
    }
}
