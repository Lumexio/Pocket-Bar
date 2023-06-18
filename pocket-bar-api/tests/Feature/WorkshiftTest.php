<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workshift;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkshiftTest extends TestCase
{
    /*
     * Refresh the database after each test.
     */
    use RefreshDatabase;
    public bool $seed = true;

    public array $ticketStructure = [
        "id",
        "status",
        "client_name",
        "ticket_date",
        "total",
        "details" => [
            "*" => [
                "nombre_articulo",
                "units",
                "unit_price",
                "discounts",
                "tax",
                "subtotal",
                "id_articulo",
                "articulos_img",
                "status",
                "barTender_id",
                "waiter_id",
                "ticket_id",
                "deleted_at",
                "created_at",
                "updated_at"
            ]
        ],
        "payments"
    ];
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_start_workshift(): void
    {
        $user = User::where("rol_id", "=", Rol::Cajero->value)->first();
        Workshift::where('active', 1)->update(['active' => 0]);
        $response = $this->actingAs($user)->post('/api/workshift/start', [
            "start_money" => 1000
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Jornada de trabajo iniciada',
        ]);
    }

    public function test_start_workshift_unauthorized(): void
    {
        $response = $this->post('/api/workshift/start', ["start_money" => 1000], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(401);
    }

    public function test_start_workshift_duplicated(): void
    {
        $user = User::where("rol_id", "=", Rol::Cajero->value)->first();
        $response = $this->actingAs($user)->post('/api/workshift/start', ["start_money" => 1000], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Ya hay una jornada de trabajo activa',
        ]);
    }

    public function test_close_workshift(): void
    {
        $user = User::where("rol_id", "=", Rol::Cajero->value)->first();
        Ticket::whereNotIn("status", [TicketStatus::Closed->value, TicketStatus::Canceled->value])->update(['status' => TicketStatus::Closed->value]);
        $response = $this->actingAs($user)->put('/api/workshift/close', ["end_money" => 1000], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'saldoFavor',
            'workshift_report' => [
                "ingresos" => [
                    "total",
                    "detail"
                ],
                "egresos" => [
                    "total",
                    "detail"
                ],
                "userTickets" => [
                    "*" => [
                        "total_workshift_sales",
                        "total_tips",
                        "id",
                        "rol",
                        "name",
                        "closed_tickets" => [
                            "*" => $this->ticketStructure
                        ],
                        "non_closed_tickets" => [
                            "*" => $this->ticketStructure
                        ],
                        "canceled_tickets" => [
                            "*" => $this->ticketStructure
                        ],
                    ]
                ]
            ],

        ]);
    }

    public function test_close_workshift_unauthorized(): void
    {
        $response = $this->put('/api/workshift/close', ["end_money" => 1000], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(401);
    }

    public function test_close_workshift_no_active(): void
    {
        $user = User::where("rol_id", "=", Rol::Cajero->value)->first();
        Workshift::where('active', 1)->update(['active' => 0]);
        $response = $this->actingAs($user)->put('/api/workshift/close', ["end_money" => 1000], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'No hay una jornada de trabajo activa',
        ]);
    }
}
