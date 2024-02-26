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
            'default' => 'required|boolean',
        ]);
        try {
            $customer_id = Auth::user()->customer_id;
            $payment_method = $request->payment_method;
            $payment_method = \Stripe\PaymentMethod::retrieve($payment_method);
            $payment_method->attach([
                'customer' => $customer_id,
            ]);
            if ($request->default) {
                \Stripe\Customer::update($customer_id, [
                    'invoice_settings' => [
                        'default_payment_method' => $payment_method->id,
                    ],
                ]);
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => 'Un error ocurrió al procesar tu solicitud.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Un error inesperado ocurrió.'], 500);
        }

        Auth::user()->paymentMethods()->create([
            'stripe_id' => $payment_method->id,
            'brand' => $payment_method->card->brand,
            'last4' => $payment_method->card->last4,
            'exp_month' => $payment_method->card->exp_month,
            'exp_year' => $payment_method->card->exp_year,
        ]);
        return response()->json([
            'message' => 'Payment method attached',
        ]);
    }

    public function setDefault(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|exists:payment_methods,stripe_id',
        ]);
        $customer_id = Auth::user()->customer_id;
        $payment_method = $request->payment_method;
        try {
            \Stripe\Customer::update($customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $payment_method,
                ],
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => 'Un error ocurrió al procesar tu solicitud.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Un error inesperado ocurrió.'], 500);
        }
        return response()->json([
            'message' => 'Default payment method updated',
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
