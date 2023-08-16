<?php

namespace App\Http\Controllers;

use App\Enums\Rol;
use App\Enums\TicketItemStatus;
use App\Enums\TicketStatus;
use App\Events\BarraEvents;
use App\Events\MeseroEvents;
use App\Models\Ticket;
use App\Http\Requests\TicketCreateRequest;
use App\Http\Requests\TicketListPwaRequest;
use App\Models\Workshift;
use App\Models\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use App\Events\ticketCreated;
use App\Events\ticketCreatedMesero;
use App\Events\ticketCreatedBarra;
use App\Http\Requests\CancelProductRequest;
use App\Http\Requests\Ordenes\ProductUpdateStatusRequest;
use App\Http\Requests\Tickets\AddProductsRequest;
use App\Http\Requests\Tickets\CancelTicketRequest;
use App\Http\Requests\Tickets\PayRequest;
use App\Http\Requests\TicketTipUpdateRequest;
use App\Models\Articulo;
use App\Models\Payment;
use App\Models\Stock;
use App\Models\TicketDetail;
use Illuminate\Support\Collection;
use Throwable;

class TicketController extends Controller
{
    public static function sendNotificationsToBarthenders()
    {
        $bartenders = User::where("rol_id", Rol::Bartender->value)->get();
        foreach ($bartenders as $bartender) {
            try {
                broadcast((new BarraEvents($bartender->id, 5))->broadcastToEveryone());
            } catch (Throwable) {
            }
        }
    }

    public function calculateGeneralData(Collection $items): array
    {
        $subtotal = $items->sum(function ($item) {
            return $item['units'] * $item['price'];
        });

        $tax = $items->sum(function ($item) {
            return $item['tax'] ?? 0;
        });

        $discounts = $items->sum(function ($item) {
            return $item['discount'] ?? 0;
        });

        $total = $subtotal + $tax - $discounts;

        return [$subtotal, $tax, $discounts, $total];
    }

    public function tipUpdate(TicketTipUpdateRequest $request): JsonResponse
    {

        $ticket = Ticket::where("id", $request->input("id"))->where("branch_id", auth()->user()->branch_id)->first();

        if (empty($ticket)) {
            return response()->json([
                "message" => "No se ha encontrado el ticket"
            ], 404);
        }

        $ticket->tip = $request->input('tip') ?? 0;
        $ticket->specifictip = $request->input('specifictip') ?? 0;
        $ticket->min_tip = round(($ticket->subtotal * $request->input('tip')) / 100, 2);
        $ticket->save();
        try {
            if (auth()->user()->rol_id == 4) {
                TicketCreatedMesero::dispatch(auth()->user()->id);
            } elseif (auth()->user()->rol_id == 5) {
                TicketCreatedBarra::dispatch(auth()->user()->id);
            }
            broadcast((new TicketCreated(auth()->user()->id))->broadcastToEveryone());
            broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
        } catch (\Throwable $th) {
        }
        return response()->json([
            "message" => "Se ha actualizado el ticket",
            "ticket" => $ticket
        ], 200);
    }

    /**
     * @throws Throwable
     * @param TicketCreateRequest $request
     * @return JsonResponse
     */
    public function store(TicketCreateRequest $request): JsonResponse
    {
        $workshift = Workshift::where("active", true)->where("branch_id", auth()->user()->branch_id)->first();
        if (!$workshift) {
            return response()->json([
                "message" => "No se ha iniciado turno de trabajo"
            ], 400);
        }
        $items = collect($request->input('products'));
        [$subtotal, $tax, $discounts, $total] = $this->calculateGeneralData($items);
        DB::beginTransaction();
        try {
            $ticket = new Ticket();
            $ticket->table_id = $request->input('table_id');
            $ticket->status = TicketStatus::Standby->value;
            $ticket->client_name = $request->input('holder');
            $ticket->user_id = auth()->user()->id;
            $ticket->ticket_date = date('Y-m-d H:i:s');
            $ticket->subtotal = $subtotal;
            $ticket->tip = $request->input('tip') ?? 0;
            $ticket->min_tip = isset($ticket->tip) ? ((float)$subtotal * (float)$ticket->tip) / 100 : 0;
            $ticket->tax = $tax;
            $ticket->discounts = $discounts;
            $ticket->item_count = $items->count();
            $ticket->timezone = "America/Denver";
            $ticket->total = $total;
            $ticket->workshift_id = $workshift->id;
            $ticket->branch_id = auth()->user()->branch_id;

            throw_if(!$ticket->save(), \Exception::class, "Error al guardar el ticket");
            $this->createTicketDetails($items, $ticket);
            DB::commit();
            //ticketCreated::dispatch(auth()->user()->id);
            try {
                if (auth()->user()->rol_id == 4) {
                    ticketCreatedMesero::dispatch(auth()->user()->id);
                    broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
                } elseif (auth()->user()->rol_id == 5) {
                    ticketCreatedBarra::dispatch(auth()->user()->id);
                    broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
                }
                broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
                broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
            } catch (\Throwable $th) {
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(["status" => 500, "error" => 1, "message" => $th->getMessage()], 500);
        }



        $this->sendNotificationsToBarthenders();

        return response()->json([
            "status" => 201,
            "error" => 0,
            "message" => "Ticket creado correctamente",
            "data" => $ticket
        ], 201);
    }

    public function index(): JsonResponse
    {
        $workshift = Workshift::where("active", true)->where("branch_id", auth()->user()->branch_id)->first();
        $tickets = Ticket::with(['details.product:id,name,price', "workshift", "payments", "user:id,name"])
            ->join('tables', 'tickets.table_id', '=', 'tables.id')
            ->where("tickets.workshift_id", $workshift->id)
            ->orderBy("ticket_date", "desc")
            ->get()
            ->toArray();
        $tickets = collect($tickets)->map(function ($ticket) {
            $data = collect($ticket)->only(['id', 'status', 'client_name', 'ticket_date', 'total', 'cancel_confirm', 'name', 'details', 'workshift', 'payments', 'user']);
            return $data;
        });
        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Listado de tickets",
            "data" => $tickets
        ], 200);
    }

    public function indexPwa(TicketListPwaRequest $request): JsonResponse
    {
        /**
         * Indexsado de tickets para la pwa
         * @var User $user
         */

        $user = auth()->user();
        $actualWorkshift = Workshift::where("active", 1)
            ->where("branch_id", $user->branch_id)
            ->first();
        $tickets = Ticket::with(['user', 'table', 'details.product', "workshift", "payments"])
            ->orderBy("ticket_date", "desc")
            ->join('tables', 'tickets.table_id', '=', 'tables.id')
            ->where("status", $request->input("status"))
            ->where("user_id", $user->id)
            ->where("workshift_id", $actualWorkshift->id ?? null)
            ->get()
            ->map(function (Ticket $ticket) {
                $data = [];
                $date = (new Carbon($ticket->ticket_date, "UTC"))->setTimezone($ticket->timezone);
                $data["id"] = $ticket->id;
                $data["nombre_mesa"] = $ticket->nombre_mesa;
                $data["status"] = $ticket->status;
                $data["titular"] = $ticket->client_name;
                $data["total"] = $ticket->total;
                $data["tip"] = $ticket->tip;
                $data["specifictip"] = $ticket->specifictip;
                $data["fecha"] = $date->toDateString();
                $data["cantidad_articulos"] = $ticket->details->count();
                $data["tiempo"] = $date->toTimeString("minute");
                $data["productos"] = $ticket->details->map(function ($item) {
                    return [
                        "id" => $item->id,
                        "nombre" => $item->articulo->nombre_articulo,
                        "cantidad" => $item->units,
                        "precio" => $item->unit_price,
                        "subtotal" => $item->subtotal,
                        "total" => $item->total,
                        "descuento" => $item->discounts,
                        "iva" => $item->tax,
                    ];
                });
                $data["pagos"] = $ticket->payments ?? null;

                return $data;
            });

        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Listado de tickets",
            "data" => $tickets
        ], 200);
    }

    private function createTicketDetails(Collection $items, Ticket $ticket)
    {
        foreach ($items as $item) {
            $ticketDetail = new \App\Models\TicketDetail();
            $ticketDetail->units = $item['units'];
            $ticketDetail->tax = $item['tax'];
            $ticketDetail->discounts = $item['discount'];
            $ticketDetail->subtotal = $item['units'] * $item['price'];
            $ticketDetail->waiter_id = auth()->user()->id;
            $ticketDetail->total = $item['units'] * $item['price'] + $item['tax'] - $item['discount'];
            $ticketDetail->product_id = $item['id'];
            $ticketDetail->status = TicketItemStatus::Standby->value;
            $ticketDetail->ticket_id = $ticket->id;
            throw_if(!$ticketDetail->save(), \Exception::class, "Error al guardar el detalle del ticket");

            $this->updateArticulo($item['id'], $item['units']);
        }
    }

    public function updateArticulo($id, $units, $sum = false)
    {
        $stock = Stock::where("branch_id", auth()->user()->branch_id)->where("product_id", $id)->first();
        if ($sum) {
            $stock->stock += $units;
        } else {
            $stock->stock -= $units;
        }
        throw_if(!$stock->save(), \Exception::class, "Error al actualizar el articulo");
    }

    public function addProducts(AddProductsRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $ticket = Ticket::where("id", $request->input("id"))->first();

        if (in_array($ticket->status, [TicketStatus::Closed->value, TicketStatus::Canceled->value])) {
            return response()->json([
                "status" => 422,
                "error" => 2,
                "message" => "Ticket se encuentra en estado {$ticket->status} y no se le pueden añadir productos"
            ], 422);
        }

        try {
            $this->createTicketDetails(collect($request->input("products")), $ticket);
            $items = $ticket->details->map(function ($item) {
                return [
                    "units" => $item->units,
                    "tax" => $item->tax,
                    "discount" => $item->discounts,
                    "price" => $item->price,
                ];
            });
            [$subtotal, $tax, $discounts, $total] = $this->calculateGeneralData($items);
            $ticket->subtotal = $subtotal;
            $ticket->tax = $tax;
            $ticket->discounts = $discounts;
            $ticket->item_count = $items->count();
            $ticket->total = $total;
            $ticket->status = TicketStatus::Standby->value;
            throw_if(!$ticket->save(), \Exception::class, "Error al guardar el ticket");
            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(["status" => 500, "error" => 1, "message" => $th->getMessage()], 500);
        }

        $this->sendNotificationsToBarthenders();

        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Producto agregado correctamente",
        ], 200);
    }

    public function cancelTicket(CancelTicketRequest $request): JsonResponse
    {

        DB::beginTransaction();

        try {

            $ticket = Ticket::with("details")->where("id", $request->input("id"))->first();


            if ($ticket->status == TicketStatus::Canceled->value || $ticket->status == TicketStatus::Closed->value) {
                return response()->json([
                    "status" => 400,
                    "error" => 1,
                    "message" => "El ticket ya ha sido cancelado",
                ], 400);
            }

            $ticket->status = TicketStatus::Canceled->value;

            throw_if(!$ticket->save(), \Exception::class, "Error al guardar el ticket");

            foreach ($ticket->details as $detail) {
                $this->updateArticulo($detail->product_id, $detail->units, true);
            }
            try {
                broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
                broadcast((new ticketCreatedBarra(auth()->user()->id))->broadcastToEveryone());
                broadcast((new ticketCreatedMesero(auth()->user()->id))->broadcastToEveryone());
                broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
            } catch (\Throwable) {
            }
            DB::commit();
        } catch (Throwable $th) {
            return response()->json([
                "status" => 500,
                "error" => 1,
                "message" => "Error al cancelar el ticket",
            ], 500);
        }
        $this->sendNotificationsToBarthenders();
        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Ticket cancelado correctamente",
        ], 200);
    }

    public function updateStatus(ProductUpdateStatusRequest $request): JsonResponse
    {

        /**
         * @var \App\Models\User $user
         */
        $user = $request->user();

        if ($user->rol_id == Rol::Mesero->value and $request->input("status") != "Recibido") {

            return response()->json([
                "error" => "No puedes cambiar el estado de un producto a menos que sea Recibido"
            ], 400);
        }

        $ticketDetail = TicketDetail::find($request->input("id"));

        $ticket = Ticket::where("id", $ticketDetail->ticket_id)->where("branch_id", auth()->user()->branch_id)->first();

        if ($ticket->status == TicketStatus::Closed->value) {

            return response()->json([
                "error" => "No puedes cambiar el estado de un producto de un ticket cerrado"
            ], 400);
        }

        if ($request->input("status") == TicketItemStatus::Received->value and $ticketDetail->waiter_id != $user->id) {

            return response()->json([
                "error" => "No puedes cambiar el estado de un producto a Recibido pues no eres el mesero que lo solicitó"
            ], 400);
        }

        if ($ticketDetail->status == TicketItemStatus::Received->value) {

            return response()->json([
                "error" => "No puedes cambiar el estado de un producto que ya ha sido recibido anteriormente"
            ], 400);
        }

        try {

            if (in_array($ticketDetail->status, [TicketItemStatus::Standby, TicketItemStatus::Prepared])) {

                $ticketDetail->barTender_id = $user->id;
            }

            if ($ticketDetail->waiter_id == $user->id and $request->input("status") == TicketItemStatus::Prepared->value and $user->rol_id == Rol::Bartender->value) {
                $ticketDetail->status = TicketItemStatus::Received->value;
            } else {

                $ticketDetail->status = $request->input("status");
            }

            throw_if(!$ticketDetail->save(), "Error al guardar en base de datos");

            $countOfStatusOfTicket = TicketDetail::countOfStatusOfTicket($ticket->id);

            $previousStatus = $ticketDetail->status;

            $ticket->status = TicketDetail::lastStatusOfTicket($ticket->id, $countOfStatusOfTicket);

            if ($previousStatus != $ticket->status) {

                throw_if(!$ticket->save(), "Error al guardar en base de datos");
            }
            try {
                broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
                broadcast((new ticketCreatedBarra(auth()->user()->id))->broadcastToEveryone());
                broadcast((new ticketCreatedMesero(auth()->user()->id))->broadcastToEveryone());
                broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
                broadcast((new MeseroEvents($ticketDetail->waiter_id))->broadcastToEveryone());
            } catch (\Throwable) {
            }
        } catch (Throwable $th) {

            return response()->json([
                "error" => $th->getMessage()
            ], 500);
        }



        $this->sendNotificationsToBarthenders();

        return response()->json($ticketDetail);
    }

    public function pay(PayRequest $request)
    {
        DB::beginTransaction();
        try {
            $ticket = Ticket::where("id", $request->input("ticket_id"))->where("branch_id", auth()->user()->branch_id)->first();
            if ($ticket->status == TicketStatus::Closed->value) {
                return response()->json([
                    "error" => "No puedes pagar un ticket cerrado"
                ], 422);
            }

            $payments = collect($request->payments);
            $totalOfPayments = $payments->sum("amount");
            $totalTip = $payments->sum("tip");
            $totalCash = $payments->where("payment_type", "cash")->sum("amount");
            $totalCard = $payments->where("payment_type", "card")->sum("amount");
            $change = $totalCash - $ticket->total;
            if ($ticket->total > $totalOfPayments) {
                return response()->json([
                    "error" => "El total de los pagos no coincide con el total del ticket"
                ], 422);
            }

            foreach ($payments as $paymentData) {
                $payment = new Payment();
                $payment->ticket_id = $ticket->id;
                $payment->type = $paymentData["payment_type"];
                $payment->vouchers = $paymentData["voucher"] ?? null;
                $payment->tip = $paymentData["tip"] ?? null;
                $payment->total = $paymentData["amount"];
                throw_if(!$payment->save(), \Exception::class, "Error al guardar el pago");
            }

            $ticket->status = TicketStatus::Closed->value;
            $ticket->cashier_id = auth()->user()->id;
            $ticket->cashier_name = auth()->user()->name;



            throw_if(!$ticket->save(), \Exception::class, "Error al guardar el ticket");
            DB::commit();
            try {
                ticketCreated::dispatch(auth()->user()->id);
                broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
                broadcast((new ticketCreatedMesero($ticket->user_id))->broadcastToEveryone());
                broadcast((new MeseroEvents($ticket->user_id))->broadcastToEveryone());
                broadcast((new ticketCreatedBarra($ticket->user_id))->broadcastToEveryone());
            } catch (\Throwable) {
            }
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                "error" => $th->getMessage()
            ], 500);
        }
        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Pago realizado correctamente",
            "data" => [
                "ticket" => $ticket,
                "total_tip" => $totalTip,
                "total_cash" => $totalCash,
                "total_card" => $totalCard,
                "change" => $change
            ]
        ], 200);
    }

    public function cancelProduct(CancelProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $ticket = Ticket::with("details")->where("id", $request->input("ticket_id"))->where("branch_id", auth()->user()->branch_id)->first();
            if (in_array($ticket->status, [TicketStatus::Closed->value, TicketStatus::Canceled->value])) {
                return response()->json([
                    "error" => "No puedes cancelar un producto de un ticket cerrado"
                ], 422);
            }
            foreach ($request->products as $product) {
                $detail = $ticket->details->where("product_id", $product["id"])->first();
                if (!isset($detail)) {
                    continue;
                }
                if ($detail->units <= $product["units"]) {
                    $this->updateArticulo($product["id"], $detail->units, true);
                    $detail->delete();
                } else {
                    $detail->units = $detail->units - $product["units"];
                    $this->updateArticulo($product["id"], $product["units"], true);
                    $detail->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
        try {
            broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
            broadcast((new ticketCreatedBarra(auth()->user()->id))->broadcastToEveryone());
            broadcast((new ticketCreatedMesero(auth()->user()->id))->broadcastToEveryone());
            broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
        } catch (\Throwable) {
        }
        DB::commit();
        $newTicket = Ticket::with("details")->find($request->input("ticket_id"));
        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Productos cancelados correctamente",
            "data" => $newTicket
        ], 200);
    }
}
