<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return response()->json([
            "message" => "success",
            "data" => $currencies
        ]);
    }

    public function show($id)
    {
        $currency = Currency::find($id);
        if ($currency) {
            return response()->json([
                "message" => "success",
                "data" => $currency
            ]);
        } else {
            return response()->json([
                "message" => "currency not found"
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            "code" => "required|string|max:3|unique:currencies,code",
            "default" => "required|boolean",
            "rate" => "required|numeric",
        ]);
        if ($request->default) {
            Currency::where("default", true)->update(["default" => false]);
        }
        $currency = Currency::create($request->all());
        return response()->json([
            "message" => "currency record created",
            "data" => $currency
        ], 201);
    }

    public function setDefault($id)
    {
        $currency = Currency::find($id);
        if ($currency) {
            Currency::where("default", true)->update(["default" => false]);
            $currency->default = true;
            $currency->save();
            return response()->json([
                "message" => "default currency set",
                "data" => $currency
            ]);
        } else {
            return response()->json([
                "message" => "currency not found"
            ], 404);
        }
    }
}
