<?php

namespace App\Http\Controllers;

use App\Http\Requests\Nominas\ToPay;
use App\Models\CashRegisterCloseData;
use App\Models\Nomina;
use App\Models\Workshift;
use DB;
use Illuminate\Http\JsonResponse;
use Throwable;

class NominasController extends Controller
{
    /**
     * @param ToPay $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function nominasToPay(ToPay $request): JsonResponse
    {
        $usersToPay = $request->input('payroll');
        $workshift = Workshift::where('active', 1)->first();
        $total = 0;
        $response = [
            "total" => $total,
            "nominas" => $request->input('payroll'),
            "workshift" => $workshift,
            "bruto" => CashRegisterCloseData::where('workshift_id', $workshift->id)->sum('total'),
            "neto" => 0
        ];
        DB::beginTransaction();
        try {
            foreach ($usersToPay as $userToPay) {
                $nomina = new Nomina();

                $nomina->user_name = $userToPay['name'];
                $nomina->user_id = $userToPay['id'];
                $nomina->base = $userToPay['nominas'];
                $nomina->paid = $request->input('total_nominas');
                $nomina->workshift_id = $workshift->id;
                //$nomina->tips = 0;
                $total = $total + $nomina->paid;
                $response["usersToPay"][] = [
                    "user_id" => $userToPay['id'],
                    "name" => $userToPay['name'],
                    "paid" => $nomina->paid
                ];
                throw_if(!$nomina->save(), "Error al guardar el registro");

            }

            $response["neto"] = $response["bruto"] - $total;
            DB::commit();
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(["error" => $th->getMessage()], 500);
        }

        return response()->json($response);
    }
}
