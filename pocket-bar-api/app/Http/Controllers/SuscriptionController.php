<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;

class SuscriptionController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'plan' => 'required|string|exists:plans,id',
        ]);
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe_customer_id = $request->user()->customer_id;
        $plan_id = $request->plan;
        $plan = \App\Models\Plan::find($plan_id);
        $intent = \Stripe\PaymentIntent::create([
            'amount' => 1099,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'customer' => $stripe_customer_id,
            'metadata' => [
                'plan_id' => $plan->id,
                'plan_stripe_id' => $plan->stripe_id,
            ]
        ]);

        return response()->json([
            'client_secret' => $intent->client_secret,
        ]);
    }
}
