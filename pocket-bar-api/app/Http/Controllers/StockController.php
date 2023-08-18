<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStock;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function addStock(UpdateStock $request)
    {
        $stock = Stock::where("branch_id", $request->branch_id)
            ->where("product_id", $request->product_id)
            ->first();
        if ($stock) {
            $stock->units += $request->units;
            $stock->save();
        } else {
            return response()->json([
                "message" => "Stock not found"
            ], 404);
        }
        return response()->json([
            "message" => "Stock updated successfully",
            "stock" => $stock
        ], 200);
    }

    public function removeStock(UpdateStock $request)
    {
        $stock = Stock::where("branch_id", $request->branch_id)
            ->where("product_id", $request->product_id)
            ->first();
        if ($stock) {
            $stock->units -= $request->units;
            $stock->save();
        } else {
            return response()->json([
                "message" => "Stock not found"
            ], 404);
        }
        return response()->json([
            "message" => "Stock updated successfully",
            "stock" => $stock
        ], 200);
    }
}
