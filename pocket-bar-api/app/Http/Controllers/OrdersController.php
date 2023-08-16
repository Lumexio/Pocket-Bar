<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ordenes\ProductListRequest;
use App\Models\TicketDetail;
use Auth;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    public function index(ProductListRequest $request): JsonResponse
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
