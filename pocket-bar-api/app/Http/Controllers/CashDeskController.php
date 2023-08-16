<?php

namespace App\Http\Controllers;

use App\Enums\Rol;
use App\Helpers\WorkshiftReport;
use DB;
use App\Models\Ticket;
use App\Models\Workshift;
use App\Http\Requests\Caja\CloseRequest;
use App\Http\Requests\Caja\MovementRequest;
use App\Models\CashRegisterCloseData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Throwable;
use Validator;

class CashDeskController extends Controller
{

    private function getMustBeData(int $userID, Workshift $actualWorkshift): JsonResponse|Collection
    {
        return Ticket::where('workshift_id', $actualWorkshift->id)
            ->where('status', '!=', 'cancelado')
            ->where("cashier_id", "=", $userID)
            ->with('details')
            ->join('payments', 'payments.ticket_id', '=', 'tickets.id')
            ->selectRaw("SUM(payments.total) as total_night, payments.type")
            ->groupBy('payments.type')
            ->get();
    }

    public function getMustBe(Request $request): JsonResponse
    {
        $request->validate([
            "branch_id" => "nullable|integer|exists:branches,id",
        ]);
        $user = auth()->user();
        $actualWorkshift = Workshift::where('active', 1)->where("branch_id", $request->input("branch_id", $user->branch_id))->first();
        if (empty($actualWorkshift)) {
            return response()->json([
                'message' => 'No hay una jornada de trabajo activa'
            ], 400);
        }
        $mustBeData = $this->getMustBeData($user->id, $actualWorkshift);
        $workshift_report = (new WorkshiftReport($actualWorkshift))->getWorkShiftReport();
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
        $user = auth()->user();
        $actualWorkshift = Workshift::where('active', 1)->where("branch_id", $user->branch_id)->first();
        if (empty($actualWorkshift)) {
            return response()->json([
                'message' => 'No hay una jornada de trabajo activa'
            ], 400);
        }
        $dataInDatabase = $this->getMustBeData($user->id, $actualWorkshift)->groupBy('type');
        $dataSended = collect($request->input('data'))->groupBy('type');

        DB::beginTransaction();
        try {
            foreach ($dataSended as $key => $cashierInfo) {
                $cashierInfo = $cashierInfo->first();
                $databaseInfo = $dataInDatabase[$key][0];
                $diff = $databaseInfo->amount - $cashierInfo->amount;
                if ($diff >= -1 and $diff <= 1) {
                    $model = new CashRegisterCloseData();
                    $model->workshift_id = $actualWorkshift->id;
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

    public function addMoney(MovementRequest $request): JsonResponse
    {
        $user = auth()->user();
        $workshift = Workshift::where('active', 1)->where("branch_id", $user->branch_id)->first();
        if (empty($workshift)) {
            return response()->json([
                'message' => 'No hay una jornada de trabajo activa'
            ], 400);
        }
        if ($user->rol_id == Rol::Guardia->value) {
            $descripcion = "Cover";
        } else {
            $descripcion = $request->input('description') ?? "Ingreso general";
        }
        $result = $request->user()->generalIncomings()->create([
            "workshift_id" => $workshift->id,
            "amount" => $request->input('amount'),
            "description" => $descripcion,
        ]);
        return response()->json([
            'message' => 'Dinero agregado correctamente',
            'data' => $result
        ], 200);
    }

    public function removeMoney(MovementRequest $request): JsonResponse
    {
        $user = auth()->user();
        $workshift = Workshift::where('active', 1)->where("branch_id", $user->branch_id)->first();
        if (empty($workshift)) {
            return response()->json([
                'message' => 'No hay una jornada de trabajo activa'
            ], 400);
        }
        $descripcion = $request->input('description') ?? "Retiro general";
        $result = $request->user()->generalIncomings()->create([
            "workshift_id" => $workshift->id,
            "amount" => -$request->input('amount'),
            "description" => $descripcion,
        ]);
        return response()->json([
            'message' => 'Dinero retirado correctamente',
            'data' => $result
        ], 200);
    }
}
