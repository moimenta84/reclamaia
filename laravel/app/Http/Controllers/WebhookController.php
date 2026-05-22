<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateDocumentJob;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(private StripeService $stripe) {}

    public function handleStripe(Request $request): Response
    {
        $signature = $request->header('Stripe-Signature');

        try {
            $event = $this->stripe->constructWebhookEvent(
                $request->getContent(),
                $signature
            );
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature failed', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        match ($event->type) {
            'payment_intent.succeeded' => $this->onPaymentSucceeded($event->data->object),
            'payment_intent.payment_failed' => $this->onPaymentFailed($event->data->object),
            'customer.subscription.created',
            'customer.subscription.updated' => $this->onSubscriptionUpdated($event->data->object),
            'customer.subscription.deleted' => $this->onSubscriptionDeleted($event->data->object),
            'invoice.payment_failed' => $this->onInvoicePaymentFailed($event->data->object),
            default => null,
        };

        return response('OK', 200);
    }

    private function onPaymentSucceeded(\Stripe\PaymentIntent $intent): void
    {
        $payment = Payment::where('stripe_payment_intent_id', $intent->id)->first();
        if (!$payment) return;

        $payment->update(['status' => Payment::STATUS_COMPLETED]);
        GenerateDocumentJob::dispatch($payment->claim_id);
    }

    private function onPaymentFailed(\Stripe\PaymentIntent $intent): void
    {
        $payment = Payment::where('stripe_payment_intent_id', $intent->id)->first();
        $payment?->update(['status' => Payment::STATUS_FAILED]);
    }

    private function onSubscriptionUpdated(\Stripe\Subscription $sub): void
    {
        $user = User::where('stripe_customer_id', $sub->customer)->first();
        if (!$user) return;

        Subscription::updateOrCreate(
            ['stripe_subscription_id' => $sub->id],
            [
                'user_id' => $user->id,
                'stripe_price_id' => $sub->items->data[0]->price->id,
                'status' => $sub->status,
                'current_period_end' => \Carbon\Carbon::createFromTimestamp($sub->current_period_end),
            ]
        );

        if ($sub->status === 'active') {
            $user->update(['plan' => 'subscriber']);
        }
    }

    private function onSubscriptionDeleted(\Stripe\Subscription $sub): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $sub->id)->first();
        if (!$subscription) return;

        $subscription->update(['status' => 'canceled', 'canceled_at' => now()]);
        $subscription->user->update(['plan' => 'free']);
    }

    private function onInvoicePaymentFailed(\Stripe\Invoice $invoice): void
    {
        $user = User::where('stripe_customer_id', $invoice->customer)->first();
        if ($user) {
            Log::warning('Subscription invoice payment failed', ['user_id' => $user->id]);
        }
    }
}
