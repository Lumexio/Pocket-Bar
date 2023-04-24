<?php

namespace App\Http\Controllers;

use App\Helpers\WorkshiftReport;
use DB;
use App\Models\Ticket;
use App\Models\Workshift;
use App\Http\Requests\Caja\CloseRequest;
use App\Models\CashRegisterCloseData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Throwable;

class CajaController extends Controller
{
    public Workshift $actualWorkshift;
    private function getMustBeData($userId): JsonResponse|Model|Collection
    {
        $this->actualWorkshift = Workshift::where('active', 1)->first();
        if (empty($this->actualWorkshift)) {
            return response()->json([
                'message' => 'No hay una jornada de trabajo activa'
            ], 400);
        }
        return Ticket::where('workshift_id', $this->actualWorkshift->id)
            ->where('status', '!=', 'cancelado')
            ->where("cashier_id", "=", $userId)
            ->with('details')
            ->join('payments_tbl', 'payments_tbl.ticket_id', '=', 'tickets_tbl.id')
            ->selectRaw("SUM(payments_tbl.total) as total_night, payments_tbl.type")
            ->groupBy('payments_tbl.type')
            ->get();
    }

    public function getMustBe(): JsonResponse
    {
        $mustBeData = $this->getMustBeData(auth()->user()->id);
        $workshift_report = (new WorkshiftReport($this->actualWorkshift))->getWorkShiftReport();
        return response()->json([
            "must_be" => $mustBeData,
            "workshift_report" => [...$workshift_report],
        ], 200);
    }

    /**
     * @throws Throwable
     */
    public function close(CloseRequest $request): JsonResponse
    {
        $dataInDatabase = $this->getMustBeData(auth()->user()->id)->groupBy('type');
        $dataSended = collect($request->input('data'))->groupBy('type');
        $activeWorkshift = Workshift::where('active', 1)->first();

        DB::beginTransaction();
        try {
            foreach ($dataSended as $key => $cashierInfo) {
                $cashierInfo = $cashierInfo->first();
                $databaseInfo = $dataInDatabase[$key][0];
                $diff = $databaseInfo->amount - $cashierInfo->amount;
                if ($diff >= -1 and $diff <= 1) {
                    $model = new CashRegisterCloseData();
                    $model->workshift_id = $activeWorkshift->id;
                    $model->type = $key;
                    $model->total = $cashierInfo->amount;
                    $model->total_tip = $cashierInfo->tip;
                    $model->total_with_tip = $cashierInfo->amount + $cashierInfo->tip;
                    $model->cashier_id = auth()->user()->id;
                    if ($key == "card") {
                        $model->vouchers = json_encode($cashierInfo->vouchers);
                    }

                    $model->save();
                    throw_if(!$model->save(), "Error al guardar el registro");
                } else {
                    throw new \Exception("El monto debe ser igual al de la base de datos");
                }
            }


            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 422);
        }

        return response()->json(['success' => 'Caja cerrada correctamente']);
    }
}
