<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
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
    public function test_user_list(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/user/', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "users" => [
                    [
                        "id",
                        "name",
                        "email",
                        "password",
                        "nominas",
                        "name_rol"
                    ]
                ]
            ]
        );
    }

    public function test_user_create(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->post('/api/user/', [
            "name" => "test",
            "email" => "farid123@gmail.com",
            "password" => "test",
            "rol_id" => 1
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                "message",
                "user" => [
                    "id",
                    "name",
                    "email",
                    "rol_id",
                    "created_at",
                    "updated_at"

                ]
            ]
        );
    }

    public function test_user_show(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/user/1', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "user" => [
                    "id",
                    "name",
                    "email",
                    "email_verified_at",
                    "created_at",
                    "updated_at",
                    "nominas",
                    "two_factor_secret",
                    "two_factor_recovery_codes",
                    "rol_id",
                    "active"
                ]
            ]
        );
    }

    public function test_user_update(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/user/1', [
            "name" => "test",
            "email" => "12345@gmail.com",
            "password" => "test",
            "rol_id" => 1
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "user" => [
                    "id",
                    "name",
                    "email",
                    "rol_id",
                    "created_at",
                    "updated_at"
                ]
            ]
        );
    }

    public function test_user_activate(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->put('/api/user/activate/1', [
            "active" => 1
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "message",
                "user" => [
                    "id",
                    "name",
                    "email",
                    "rol_id",
                    "created_at",
                    "updated_at"
                ]
            ]
        );
    }
}
