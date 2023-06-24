<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;
    public bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ticket_list(): void
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->get('/api/tickets/list');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "status",
            "error",
            "message",
            "data" => [
                "*" => [
                    "id",
                    "status",
                    "client_name",
                    "user_name",
                    "ticket_date",
                    "total",
                    "cancel_confirm",
                    "nombre_mesa",
                    "details" => [
                        "*" => [
                            "id",
                            "units",
                            "unit_price",
                            "discounts",
                            "tax",
                            "subtotal",
                            "total",
                            "articulos_tbl_id",
                            "articulos_img",
                            "status",
                            "barTender_id",
                            "waiter_id",
                            "ticket_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "articulo" => [
                                "id",
                                "nombre_articulo",
                                "precio_articulo",
                            ]
                        ]
                    ],
                    "workshift",
                    "payments"
                ]
            ]
        ]);
    }

    public function test_ticket_list_unauthoraized(): void
    {
        $response = $this->get('/api/tickets/list');
        $response->assertStatus(302);
    }

    public function test_ticket_list_pwa()
    {
        $user = User::where("rol_id", Rol::Administrativo->value)->first();
        $response = $this->actingAs($user)->json('GET', '/api/tickets/pwa/list', [
            "status" => "Entregado",
        ], [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "status",
            "error",
            "message",
            "data" => [
                "*" => [
                    "id",
                    "status",
                    "client_name",
                    "user_name",
                    "ticket_date",
                    "total",
                    "cancel_confirm",
                    "nombre_mesa",
                    "details" => [
                        "*" => [
                            "id",
                            "units",
                            "unit_price",
                            "discounts",
                            "tax",
                            "subtotal",
                            "total",
                            "articulos_tbl_id",
                            "articulos_img",
                            "status",
                            "barTender_id",
                            "waiter_id",
                            "ticket_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "articulo" => [
                                "id",
                                "nombre_articulo",
                                "precio_articulo",
                            ]
                        ]
                    ],
                    "workshift",
                    "payments"
                ]
            ]
        ]);
    }

    public function test_ticket_create(): void
    {
        $user = User::where("rol_id", Rol::Mesero->value)->first();
        $response = $this->actingAs($user)->post('/api/tickets/create', [
            "mesa_id" => 1,
            "titular" => "Test",
            "productos" => [
                [
                    "id" => 1,
                    "nombre_articulo" => "Test",
                    "precio_articulo" => 10,
                    "foto_articulo" => "https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png",
                    "descuento" => 0,
                    "tax" => 0,
                    "piezas" => 1,
                ],
                [
                    "id" => 2,
                    "nombre_articulo" => "Test",
                    "precio_articulo" => 10,
                    "foto_articulo" => "https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png",
                    "descuento" => 0,
                    "tax" => 0,
                    "piezas" => 1,
                ],
            ]
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            "status",
            "error",
            "message",
            "data" => [
                "id",
                "status",
                "client_name",
                "user_name",
                "user_id",
                "ticket_date",
                "subtotal",
                "total",
                "workshift_id",
                "timezone",
                "item_count",
                "tax",
                "tip",
                "discounts",
                "updated_at",
                "created_at",
            ]
        ]);
    }

    public function test_ticket_create_unauthoraized(): void
    {
        $request = [
            "mesa_id" => 1,
            "titular" => "Test",
            "productos" => [
                [
                    "id" => 1,
                    "nombre_articulo" => "Test",
                    "precio_articulo" => 10,
                    "foto_articulo" => "https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png",
                    "descuento" => 0,
                    "tax" => 0,
                    "piezas" => 1,
                ],
                [
                    "id" => 2,
                    "nombre_articulo" => "Test",
                    "precio_articulo" => 10,
                    "foto_articulo" => "https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png",
                    "descuento" => 0,
                    "tax" => 0,
                    "piezas" => 1,
                ],
            ]
        ];
        $usersType = [
            Rol::Administrativo->name,
            Rol::Cajero->name,
        ];
        $response = $this->post('/api/tickets/create', $request, [
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(401);
        foreach ($usersType as $type) {
            $user = User::where("name", "=", $type)->first();
            $response = $this->actingAs($user)->post('/api/tickets/create', $request);
            $response->assertStatus(403);
        }
    }

    public function test_ticket_tip(): void
    {
        $user = User::where("rol_id", Rol::Mesero->value)->first();
        $ticket = Ticket::where("user_id", "=", $user->id)->first();
        $response = $this->actingAs($user)->put('/api/tickets/tip', [
            "id" => $ticket->id,
            "tip" => 10,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "ticket" => [
                "id",
                "total",
                "subtotal",
                "item_count",
                "user_name",
                "timezone",
                "ticket_date",
                "user_id",
                "tax",
                "discounts",
                "tip",
                "specifictip",
                "min_tip",
                "client_name",
                "cashier_name",
                "cashier_id",
                "mesa_id",
                "status",
                "closed",
                "cancel_confirm",
                "canceled_by_cashier_at",
                "canceled_by_cashier_id",
                "canceled_by_admin_at",
                "canceled_by_admin_id",
                "workshift_id",
                "created_at",
                "updated_at",
            ]
        ]);
    }

    public function test_ticket_tip_unauthoraized(): void
    {
        $request = [
            "id" => 1,
            "tip" => 10,
        ];
        $usersType = [
            Rol::Administrativo->name,
            Rol::Cajero->name,
        ];
        $response = $this->put('/api/tickets/tip', $request, [
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(401);
        foreach ($usersType as $type) {
            $user = User::where("name", "=", $type)->first();
            $response = $this->actingAs($user)->put('/api/tickets/tip', $request);
            $response->assertStatus(403);
        }
    }

    public function test_ticket_pay(): void
    {
        $user = User::where("rol_id", Rol::Cajero->value)->first();
        $ticket = Ticket::where("status", "!=", TicketStatus::Closed)->first();
        $response = $this->actingAs($user)->post('/api/tickets/pay', [
            "ticket_id" => $ticket->id,
            "payments" => [
                [
                    "payment_type" => "card",
                    "amount" => $ticket->total,
                    "voucher" => "1234567890",
                    "tip" => 1000,
                ],
            ]
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "status",
            "error",
            "data" => [
                "id",
                "total",
                "subtotal",
                "item_count",
                "user_name",
                "timezone",
                "ticket_date",
                "user_id",
                "tax",
                "discounts",
                "tip",
                "specifictip",
                "min_tip",
                "client_name",
                "cashier_name",
                "cashier_id",
                "mesa_id",
                "status",
                "closed",
                "cancel_confirm",
                "canceled_by_cashier_at",
                "canceled_by_cashier_id",
                "canceled_by_admin_at",
                "canceled_by_admin_id",
                "workshift_id",
                "created_at",
                "updated_at",
            ]
        ]);
    }

    public function test_ticket_pay_unauthoraized(): void
    {
        $request = [
            "ticket_id" => 1,
            "payments" => [
                [
                    "payment_type" => "card",
                    "amount" => 100,
                    "voucher" => "1234567890",
                    "tip" => 1000,
                ],
            ]
        ];
        $usersType = [
            Rol::Administrativo->name,
            Rol::Mesero->name,
            Rol::Bartender->name,
        ];
        $response = $this->post('/api/tickets/pay', $request, [
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(401);
        foreach ($usersType as $type) {
            $user = User::where("name", "=", $type)->first();
            $response = $this->actingAs($user)->post('/api/tickets/pay', $request);
            $response->assertStatus(403);
        }
    }

    public function test_ticket_cancel(): void
    {
        $user = User::where("name", "=", "cajero")->first();
        $ticket = Ticket::where("status", "!=", TicketStatus::Closed)->first();
        $response = $this->actingAs($user)->post('/api/tickets/cancel', [
            "id" => $ticket->id,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "status",
            "error",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "status",
            "error",
        ]);
        $this->assertDatabaseHas("tickets_tbl", [
            "id" => $ticket->id,
            "status" => TicketStatus::Canceled,
        ]);
    }

    public function test_ticket_addProduct(): void
    {
        $user = User::where("name", "=", "mesero")->first();
        $ticket = Ticket::where("status", TicketStatus::Standby)->first();
        $response = $this->actingAs($user)->put('/api/tickets/add/products', [
            "ticket_id" => $ticket->id,
            "productos" => [
                [
                    "id" => 1,
                    "piezas" => 1,
                    "precio_articulo" => 100,
                    "nombre_articulo" => "Producto 1",
                    "foto_articulo" => "producto",
                    "descuento" => 0,
                    "tax" => 0,
                ]
            ]
        ], [
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "status",
            "error",
        ]);
    }

    public function test_ticket_addProduct_unauthoraized(): void
    {
        $request = [
            "ticket_id" => 1,
            "productos" => [
                [
                    "id" => 1,
                    "piezas" => 1,
                    "precio_articulo" => 100,
                    "nombre_articulo" => "Producto 1",
                    "foto_articulo" => "producto",
                    "descuento" => 0,
                    "tax" => 0,
                ]
            ]
        ];
        $usersType = [
            Rol::Administrativo->name,
            Rol::Cajero->name,
        ];
        $response = $this->put('/api/tickets/add/products', $request, [
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(401);
        foreach ($usersType as $type) {
            $user = User::where("name", "=", $type)->first();
            $response = $this->actingAs($user)->put('/api/tickets/add/products', $request);
            $response->assertStatus(403);
        }
    }
}
