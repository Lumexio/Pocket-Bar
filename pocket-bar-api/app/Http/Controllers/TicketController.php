<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketCreateRequest;
use App\Models\Table;
use App\Models\Workshift;
use DB;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function store(TicketCreateRequest $request): JsonResponse
    {
        $items = collect($request->input('items'));

        $subtotal = $items->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        $tax = $items->sum(function ($item) {
            return $item['tax'];
        });

        $discounts = $items->sum(function ($item) {
            return $item['discount'];
        });

        $total = $subtotal + $tax - $discounts;

        $table = Table::find($request->input('tableId'));
        DB::beginTransaction();
        try {
            $ticket = new Ticket();
            $ticket->table_id = $table->id;
            $ticket->table_name = $table->name;
            $ticket->status = "Pedido nuevo";
            $ticket->user_id = auth()->user()->id;
            $ticket->ticket_date = date('Y-m-d H:i:s');
            $ticket->subtotal = $subtotal;
            $ticket->min_tip = $subtotal >= 500 ? $subtotal * 0.10 : $subtotal;
            $ticket->tax = $tax;
            $ticket->discounts = $discounts;
            $ticket->total = $total;
            $ticket->workshift_id = Workshift::where("active", 1)->firstOrFail()->id;

            throw_if(!$ticket->save(), \Exception::class, "Error al guardar el ticket");


            foreach ($items as $item) {
                $ticketDetail = new \App\Models\TicketDetail();
                $ticketDetail->units = $item['quantity'];
                $ticketDetail->unit_price = $item['price'];
                $ticketDetail->tax = $item['tax'];
                $ticketDetail->discounts = $item['discount'];
                $ticketDetail->total = $item['quantity'] * $item['price'] + $item['tax'] - $item['discount'];
                $ticketDetail->articulos_tbl_id = $item['id'];
                $ticketDetail->articulos_img = $item["image"];
                $ticketDetail->attended = 0;
                $ticketDetail->ticket_id = $ticket->id;
                throw_if(!$ticketDetail->save(), \Exception::class, "Error al guardar el detalle del ticket");
            }
            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(["status" => 500, "error" => 1, "message" => $th->getMessage()], 500);
        }

        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Ticket creado correctamente",
            "data" => $ticket
        ], 200);
    }


    public function update($request, $ticket): JsonResponse
    {
        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Ticket actualizado correctamente",
            "data" => null
        ], 200);
    }
}
