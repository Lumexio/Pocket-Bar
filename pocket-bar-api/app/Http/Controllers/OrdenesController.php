<?php

namespace App\Http\Controllers;

use App\Events\BarraEvents;
use App\Events\MeseroEvents;
use App\Http\Requests\Ordenes\ProductoListRequest;
use App\Http\Requests\Ordenes\ProductoUpdateStatusRequest;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Auth;
use DB;
use Illuminate\Http\JsonResponse;

class OrdenesController extends Controller
{
    public function index(ProductoListRequest $request): JsonResponse
    {
        return response()
            ->json(
                TicketDetail::getListForWebSockets(
                    $request->input('status'),
                    Auth::user()->id,
                    Auth::user()->rol_id
                )
            );
    }
}
