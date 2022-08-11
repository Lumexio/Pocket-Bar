<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Ticket;
use App\Models\Workshift;
use App\Http\Requests\Caja\CloseRequest;
use App\Models\CashRegisterCloseData;

class CajaController extends Controller
{
    private function getMustBeData($userId)
    {
        $actualWorkshift = Workshift::where('active', 1)->first();
        $tickets = Ticket::where('workshift_id', $actualWorkshift->id)
            ->where('status', '!=', 'cancelado')
            ->where("cashier_id", "=", $userId)
            ->with('details')
            ->join('payments_tbl', 'payments_tbl.ticket_id', '=', 'tickets_tbl.id')
            ->selectRaw("SUM(payments_tbl.amount), payments_tbl.type")
            ->groupBy('payments_tbl.type')
            ->get();
        return $tickets;
    }

    public function getMustBe()
    {
        return response()->json($this->getMustBeData(auth()->user()->id));
    }

    public function close(CloseRequest $request)
    {
        $dataInDatabase = $this->getMustBeData(auth()->user()->id)->groupBy('type');
        $dataSended = collect($request->input('data'))->groupBy('type');
        $activeWorkshift = Workshift::where('active', 1)->first();

        DB::beginTransaction();
        try {
            foreach ($dataSended as $key => $cashierInfo) {
                $databaseInfo = $dataInDatabase[$key][0];
                $diff = $databaseInfo->amount - $cashierInfo[0]->amount;
                if ($diff >= -1 and $diff <= 1) {
                    $model = new CashRegisterCloseData();
                    $model->workshift_id = $activeWorkshift->id;
                    $model->type = $key;
                    $model->amount = $cashierInfo[0]->amount;
                    $model->cashier_id = auth()->user()->id;
                    if ($key == "card") {
                        $model->vouchers = json_encode($cashierInfo[0]->vouchers);
                    }

                    $model->save();
                    throw_if(!$model->save(), "Error al guardar el registro");
                } else {
                    throw new \Exception("El monto debe ser igual al de la base de datos");
                }
            }


            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 422);
        }

        return response()->json(['success' => 'Caja cerrada correctamente']);
    }
}
