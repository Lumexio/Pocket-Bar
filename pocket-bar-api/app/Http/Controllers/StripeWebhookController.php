<?php

namespace App\Http\Controllers;

use App\Models\Subscription as ModelsSubscription;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Subscription;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                break;
            case 'customer.subscription.updated':
                $subscription = $event->data->object; // contains a \Stripe\Subscription
                $this->handleSubscriptionUpdate($subscription);
                break;
            case 'customer.subscription.created':
                $subscription = $event->data->object; // contains a \Stripe\Subscription
                $this->handleSubsriptionCreated($subscription);
                break;

            case 'customer.subscription.deleted':
                $subscription = $event->data->object; // contains a \Stripe\Subscription
                $this->handleSubscriptionDeleted($subscription);
                break;
            default:
                // Unexpected event type
                return response()->json(['error' => 'Unexpected event type'], 400);
        }

        return response()->json(['status' => 'success']);
    }

    private function handleSubscriptionUpdate(Subscription $subscription)
    {
        // verify if the subscription is past_due and send an email to the user
        if ($subscription->status === 'past_due') {
            // update the user's subscription status
            $userSubscription = ModelsSubscription::where('stripe_id', $subscription->id)->first();
            $userSubscription->stripe_status = $subscription->status;
            $userSubscription->save();
            // send an email to the user
            $message = 'Your subscription is past due. Please update your payment information.';
        }
    }

    private function handleSubsriptionCreated(Subscription $subscription)
    {
        // verify if the subscription is incomplete and send an email to the user
        if ($subscription->status === 'incomplete') {
            // update the user's subscription status
            $userSubscription = ModelsSubscription::where('stripe_id', $subscription->id)->first();
            $userSubscription->stripe_status = $subscription->status;
            $userSubscription->save();
            // send an email to the user
            $message = 'Your subscription is incomplete. Please update your payment information.';
        }
    }

    private function handleSubscriptionDeleted(Subscription $subscription)
    {
        if ($subscription->status === 'canceled') {
            // update the user's subscription status, end date, and expiration date
            $userSubscription = ModelsSubscription::where('stripe_id', $subscription->id)->first();
            $userSubscription->stripe_status = $subscription->status;
            $userSubscription->ends_at = $subscription->ended_at;
            $userSubscription->expiration_date = $subscription->ended_at;
            $userSubscription->save();
            // send an email to the user
            $message = 'Your subscription has been canceled.';
        }
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        // update the user's subscription status, end date, and expiration date
        $userSubscription = ModelsSubscription::where('stripe_id', $paymentIntent->subscription)->first();
        $userSubscription->stripe_status = $paymentIntent->status;
        $userSubscription->ends_at = $paymentIntent->current_period_end;
        $userSubscription->expiration_date = $paymentIntent->current_period_end;
        $userSubscription->save();
    }
}
