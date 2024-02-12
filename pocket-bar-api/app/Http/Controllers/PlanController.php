<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        /* example of response
        "beerTier": {
            "yearly": {
                "id": "beerTierYearly",
                "price": 100,
            },
            "monthly": {
                "id": "beerTierMonthly",
                "price": 10,
            }
            "benefits": [
                "Access to all beers",
                "Access to all events",
                "Access to all beer tastings",
            ]
        },
        */
        $plans = collect(Plan::all())->groupBy('name')->map(function ($plan) {
            $yearly = $plan->where('interval', 'year')->first();
            $monthly = $plan->where('interval', 'month')->first();
            return [
                'yearly' => [
                    'id' => $yearly->stripe_id,
                    'price' => $yearly->amount,
                ],
                'monthly' => [
                    'id' => $monthly->stripe_id,
                    'price' => $monthly->amount,
                ],
                'benefits' => explode(',', $yearly->benefits),
            ];
        })->values()->all();
        return response()->json(['message' => 'success', 'data' => $plans]);
    }

    public function show($id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        return response()->json(['message' => 'success', 'data' => $plan]);
    }
}
