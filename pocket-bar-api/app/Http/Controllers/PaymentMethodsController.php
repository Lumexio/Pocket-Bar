<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
    public function createSetupIntent()
    {
        $customer_id = Auth::user()->customer_id;
        $intent = \Stripe\SetupIntent::create([
            'customer' => $customer_id,
            'usage' => 'off_session',
            'payment_method_types' => ['card'],
        ]);
        return response()->json([
            'message' => 'Setup intent created',
            'client_secret' => $intent->client_secret,
        ]);
    }

    public function attachPaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);
        $customer_id = Auth::user()->customer_id;
        $payment_method = $request->payment_method;
        $payment_method = \Stripe\PaymentMethod::retrieve($payment_method);
        $payment_method->attach([
            'customer' => $customer_id,
        ]);

        return response()->json([
            'message' => 'Payment method attached',
        ]);
        Auth::user()->paymentMethods()->create([
            'stripe_id' => $payment_method->id,
            'brand' => $payment_method->card->brand,
            'last4' => $payment_method->card->last4,
            'exp_month' => $payment_method->card->exp_month,
            'exp_year' => $payment_method->card->exp_year,
        ]);
    }

    public function index()
    {
        $payment_methods = Auth::user()->paymentMethods()->get();
        return response()->json([
            'message' => 'Payment methods retrieved',
            'payment_methods' => $payment_methods,
        ]);
    }
}
