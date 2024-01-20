<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    /**
     * @deprecated
     */
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

    public function createSubscription(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|exists:payment_methods,stripe_id',
            'plan' => 'required|string|exists:plans,id',
        ]);

        $plan = \App\Models\Plan::find($request->plan);
        $stripe_customer_id = $request->user()->customer_id;
        $payment_method = $request->payment_method;

        $stripeSuscription = \Stripe\Subscription::create([
            'customer' => $stripe_customer_id,
            'items' => [
                [
                    'plan' => $plan->stripe_id,
                ],
            ],
            'default_payment_method' => $payment_method,
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        if ($stripeSuscription->latest_invoice->payment_intent->status == 'succeeded') {
            $suscription = new \App\Models\Subscription();
            $suscription->name = $plan->name;
            $suscription->tenant_user_id = $request->user()->id;
            $suscription->stripe_id = $stripeSuscription->id;
            $suscription->stripe_plan = $stripeSuscription->plan->id;
            $suscription->quantity = $stripeSuscription->quantity;
            $suscription->stripe_status = $stripeSuscription->status;
            $suscription->ends_at = $stripeSuscription->current_period_end;
            $suscription->expiration_date = $stripeSuscription->current_period_end;
            $suscription->save();
        } else {
            return response()->json([
                'message' => 'The payment was not successful',
            ], 400);
        }
    }
}
