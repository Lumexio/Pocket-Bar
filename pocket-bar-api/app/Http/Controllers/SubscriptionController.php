<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
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

        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

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

    public function cancelSubscription(Request $request)
    {
        $request->validate([
            'subscription' => 'required|string|exists:subscriptions,stripe_id',
        ]);

        $stripe_subscription_id = $request->subscription;
        $stripe_subscription = \Stripe\Subscription::retrieve($stripe_subscription_id);
        $stripe_subscription->cancel();

        return response()->json([
            'message' => 'Subscription canceled',
        ]);
    }
}
