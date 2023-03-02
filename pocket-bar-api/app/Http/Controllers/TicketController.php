<?php

namespace App\Http\Controllers;

use App\Enums\Rol;
use App\Enums\TicketItemStatus;
use App\Enums\TicketStatus;
use App\Events\BarraEvents;
use App\Events\MeseroEvents;
use App\Models\Ticket;
use Illuminate\Http\Request;
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
use App\Http\Requests\Ordenes\ProductoUpdateStatusRequest;
use App\Http\Requests\Tickets\AddProductsRequest;
use App\Http\Requests\Tickets\CancelTicketRequest;
use App\Http\Requests\Tickets\PayRequest;
use App\Models\Articulo;
use App\Models\Payment;
use App\Models\TicketDetail;
use Illuminate\Support\Collection;
use Throwable;

class TicketController extends Controller
{
    public static function sendNotificationsToBarthenders()
    {
        $bartenders = User::where("rol_id", Rol::Bartender->value)->get();
        foreach ($bartenders as $bartender) {
            broadcast((new BarraEvents($bartender->id, 5))->broadcastToEveryone());
        }
    }

    public function calculateGeneralData(Collection $items): array
    {
        $subtotal = $items->sum(function ($item) {
            return $item['piezas'] * $item['precio_articulo'];
        });

        $tax = $items->sum(function ($item) {
            return $item['tax'] ?? 0;
        });

        $discounts = $items->sum(function ($item) {
            return $item['descuento'] ?? 0;
        });

        $total = $subtotal + $tax - $discounts;

        return [$subtotal, $tax, $discounts, $total];
    }
    public function tipUpdate(Request $request)
    {

        $ticket = Ticket::findOrFail($request->input('id'));

        $ticket->tip = $request->input('tip');
        $ticket->specifictip = $request->input('specifictip');
        (float)$ticket->min_tip = ((float)$ticket->subtotal*(float)$request->input('tip'))/100;
        $ticket->save();
        if (auth()->user()->rol_id == 4) {
            ticketCreatedMesero::dispatch(auth()->user()->id);
            broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
        } else if (auth()->user()->rol_id == 5) {
            ticketCreatedBarra::dispatch(auth()->user()->id);
            broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
        }
        broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
        broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
        return response()->json($ticket);
    }

    /**
     * @throws Throwable
     * @param TicketCreateRequest $request
     * @return JsonResponse
     */
    public function store(TicketCreateRequest $request): JsonResponse
    {
        $workshift = Workshift::where("active", true)->first();
        if (!$workshift) {
            return response()->json([
                "message" => "No se ha iniciado turno de trabajo"
            ], 400);
        }
        //Cambiar migraciòn de tickets eliminando nombre_mesa
        $items = collect($request->input('productos'));
        [$subtotal, $tax, $discounts, $total] = $this->calculateGeneralData($items);
        // $table = Mesa::find();
        DB::beginTransaction();
        try {
            $ticket = new Ticket();
            $ticket->mesa_id = $request->input('mesa_id');
            //$ticket->nombre_mesa = $table->nombre_mesa;
            $ticket->status = TicketStatus::Standby->value;
            $ticket->client_name = $request->input('titular');
            $ticket->user_id = auth()->user()->id;
            $ticket->user_name = auth()->user()->name;
            $ticket->ticket_date = date('Y-m-d H:i:s');
            $ticket->subtotal = $subtotal;
            $ticket->tip = $request->input('tip');
            $ticket->min_tip = $ticket->tip!=null ? ((float)$subtotal * (float)$ticket->tip)/100 : 0;
            $ticket->tax = $tax;
            $ticket->discounts = $discounts;
            $ticket->item_count = $items->count();
            $ticket->timezone = "America/Denver";
            $ticket->total = $total;
            $ticket->workshift_id = $workshift->id;

            throw_if(!$ticket->save(), \Exception::class, "Error al guardar el ticket");
            $this->createTicketDetails($items, $ticket);
            DB::commit();
            //ticketCreated::dispatch(auth()->user()->id);
            if (auth()->user()->rol_id == 4) {
                ticketCreatedMesero::dispatch(auth()->user()->id);
                broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
            } else if (auth()->user()->rol_id == 5) {
                ticketCreatedBarra::dispatch(auth()->user()->id);
                broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
            }
            broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
            broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(["status" => 500, "error" => 1, "message" => $th->getMessage()], 500);
        }



        $this->sendNotificationsToBarthenders();

        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Ticket creado correctamente",
            "data" => $ticket
        ]);
    }

    public function index(Request $request): JsonResponse
    {

        $tickets = Ticket::with(['details.articulo:id,nombre_articulo,precio_articulo', "workshift", "payments"])
            ->leftJoin('mesas_tbl', 'tickets_tbl.mesa_id', '=', 'mesas_tbl.id')
            ->select('tickets_tbl.id', 'tickets_tbl.status', 'tickets_tbl.client_name', 'tickets_tbl.user_name', 'tickets_tbl.ticket_date', 'tickets_tbl.total', 'tickets_tbl.cancel_confirm', 'mesas_tbl.nombre_mesa')
            ->orderBy("ticket_date", "desc")
            ->get();

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
         * @var User
         */

        $user = auth()->user();
        $actualWorkshift = Workshift::where("active", 1)->first();
        $tickets = Ticket::with(['user', 'table', 'details.articulo', "workshift", "payments"])
            ->orderBy("ticket_date", "desc")
            ->leftJoin('mesas_tbl', 'tickets_tbl.mesa_id', '=', 'mesas_tbl.id')
            ->select('tickets_tbl.id', 'tickets_tbl.status','tickets_tbl.tip','tickets_tbl.specifictip', 'tickets_tbl.client_name', 'tickets_tbl.user_name', 'tickets_tbl.ticket_date', 'tickets_tbl.total', 'mesas_tbl.nombre_mesa',)
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
            $ticketDetail->units = $item['piezas'];
            $ticketDetail->unit_price = $item['precio_articulo'];
            $ticketDetail->tax = $item['tax'];
            $ticketDetail->discounts = $item['descuento'];
            $ticketDetail->subtotal = $item['piezas'] * $item['precio_articulo'];
            $ticketDetail->waiter_id = auth()->user()->id;
            $ticketDetail->total = $item['piezas'] * $item['precio_articulo'] + $item['tax'] - $item['descuento'];
            $ticketDetail->articulos_tbl_id = $item['id'];
            $ticketDetail->articulos_img = $item["foto_articulo"];
            $ticketDetail->status = TicketItemStatus::Standby->value;
            $ticketDetail->ticket_id = $ticket->id;
            throw_if(!$ticketDetail->save(), \Exception::class, "Error al guardar el detalle del ticket");

            $this->updateArticulo($item['id'], $item['piezas']);
        }
    }

    public function updateArticulo($id, $units, $sum = false)
    {
        $articulo = Articulo::find($id);
        if ($sum) {
            $articulo->cantidad_articulo += $units;
        } else {
            $articulo->cantidad_articulo -= $units;
        }
        throw_if(!$articulo->save(), \Exception::class, "Error al actualizar el articulo");
    }

    public function addProducts(AddProductsRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $ticket = Ticket::find($request->input('ticket_id'));

        if (in_array($ticket->status, [TicketStatus::Closed->value, TicketStatus::Canceled->value])) {
            return response()->json([
                "status" => 422,
                "error" => 2,
                "message" => "Ticket se encuentra en estado {$ticket->status} y no se le pueden añadir productos"
            ], 422);
        }

        try {
            $this->createTicketDetails(collect($request->input("productos")), $ticket);
            $items = $ticket->details->map(function ($item) {
                return [
                    "piezas" => $item->units,
                    "precio_articulo" => $item->unit_price,
                    "tax" => $item->tax,
                    "descuento" => $item->discounts,
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

            $ticket = Ticket::with("details")->find($request->input('id'));


            if ($ticket->status == TicketStatus::Canceled->value) {
                return response()->json([
                    "status" => 500,
                    "error" => 1,
                    "message" => "El ticket ya ha sido cancelado",
                ], 500);
            } else if (auth()->user()->rol_id === Rol::Cajero->value and $ticket->cancel_confirm === false) {
                return response()->json([
                    "status" => 500,
                    "error" => 1,
                    "message" => "El ticket ya ha sido cancelado por el cajero",
                ], 500);
            }



            if (auth()->user()->rol_id === Rol::Administrativo->value and $ticket->cancel_confirm === true) {
                return response()->json([
                    "status" => 500,
                    "error" => 1,
                    "message" => "El ticket ya ha sido cancelado por el administrador",
                ], 500);
            }

            if (auth()->user()->rol_id == Rol::Cajero->value) {
                $ticket->cancel_confirm = false;
                $ticket->canceled_by_cashier_at = Carbon::now();
                $ticket->canceled_by_cashier_id = auth()->user()->id;
            }



            if (in_array(auth()->user()->rol_id, [Rol::Administrativo->value, Rol::Gerencia->value])) {
                TicketDetail::where("ticket_id", $ticket->id)->update(["status" => TicketItemStatus::Canceled->value]);
                $ticket->cancel_confirm = true;
                $ticket->canceled_by_admin_at = Carbon::now();
                $ticket->canceled_by_admin_id = auth()->user()->id;
                $ticket->status = TicketStatus::Canceled->value;
            }

            throw_if(!$ticket->save(), \Exception::class, "Error al guardar el ticket");

            if ($ticket->status === TicketStatus::Canceled->value) {
                /**
                 * @var TicketDetail $detail
                 */
                foreach ($ticket->details as $detail) {
                    $this->updateArticulo($detail->articulos_tbl_id, $detail->units, true);
                }
            }
            broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
            broadcast((new ticketCreatedBarra(auth()->user()->id))->broadcastToEveryone());
            broadcast((new ticketCreatedMesero(auth()->user()->id))->broadcastToEveryone());
            broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
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

    public function updateStatus(ProductoUpdateStatusRequest $request): JsonResponse
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

        $ticket = Ticket::find($ticketDetail->ticket_id);

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
            if ($user->rol_id == Rol::Mesero->value) {
                broadcast((new ticketCreatedMesero(auth()->user()->id))->broadcastToEveryone());
                broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
            } else if ($user->rol_id == Rol::Bartender->value) {
                broadcast((new ticketCreatedBarra(auth()->user()->id))->broadcastToEveryone());
                broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
            }
            broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
            broadcast((new ticketCreatedBarra(auth()->user()->id))->broadcastToEveryone());
            broadcast((new ticketCreatedMesero(auth()->user()->id))->broadcastToEveryone());
            broadcast((new MeseroEvents(auth()->user()->id))->broadcastToEveryone());
        } catch (Throwable $th) {

            return response()->json([
                "error" => $th->getMessage()
            ], 500);
        }

        broadcast((new MeseroEvents($ticketDetail->waiter_id))->broadcastToEveryone());

        $this->sendNotificationsToBarthenders();

        return response()->json($ticketDetail);
    }

    public function pay(PayRequest $request)
    {

        DB::beginTransaction();
        try {
            $ticket = Ticket::find($request->input("ticket_id"));
            if ($ticket->status == TicketStatus::Closed->value) {
                return response()->json([
                    "error" => "No puedes pagar un ticket cerrado"
                ], 422);
            }

            $payments = collect($request->payments);
            $totalOfPayments = $payments->sum("amount");
            if ($ticket->total != $totalOfPayments) {
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
            //ticketCreated::dispatch(auth()->user()->id);
            broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
            # code...
            broadcast((new ticketCreatedMesero($ticket->user_id))->broadcastToEveryone());
            broadcast((new MeseroEvents($ticket->user_id))->broadcastToEveryone());

            # code...
            broadcast((new ticketCreatedBarra($ticket->user_id))->broadcastToEveryone());
            //broadcast((new MeseroEvents($ticket->user_id))->broadcastToEveryone());

            //broadcast((new ticketCreated(auth()->user()->id))->broadcastToEveryone());
            //broadcast((new MeseroEvents($ticket->user_id))->broadcastToEveryone());
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
            "data" => $ticket
        ], 200);
    }
}
