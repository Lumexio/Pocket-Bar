<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ordenes\ProductoListRequest;
use App\Models\TicketDetail;
use Auth;
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
