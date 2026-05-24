<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Customer;
use Stripe\Subscription;

class StripeService
{
    public function __construct()
    {
        if (!class_exists(Stripe::class)) {
            return;
        }
        $key = config('services.stripe.secret');
        if (!empty($key)) {
            Stripe::setApiKey($key);
        }
    }

    private function assertConfigured(): void
    {
        if (empty(config('services.stripe.secret'))) {
            throw new \RuntimeException('STRIPE_SECRET no configurado en .env');
        }
    }

    public function createPaymentIntent(int $claimId, int $amountCents = 999, string $currency = 'eur'): PaymentIntent
    {
        $this->assertConfigured();
        return PaymentIntent::create([
            'amount' => $amountCents,
            'currency' => $currency,
            'metadata' => ['claim_id' => $claimId],
            'automatic_payment_methods' => ['enabled' => true],
        ]);
    }

    public function refundPaymentIntent(string $paymentIntentId): Refund
    {
        $this->assertConfigured();
        return Refund::create(['payment_intent' => $paymentIntentId]);
    }

    public function createOrRetrieveCustomer(string $email, ?string $name = null): Customer
    {
        $this->assertConfigured();
        $existing = Customer::search(['query' => "email:'{$email}'"]);
        if ($existing->data) {
            return $existing->data[0];
        }

        return Customer::create(array_filter([
            'email' => $email,
            'name' => $name,
        ]));
    }

    public function createSubscription(string $customerId, string $priceId): Subscription
    {
        $this->assertConfigured();
        return Subscription::create([
            'customer' => $customerId,
            'items' => [['price' => $priceId]],
            'payment_behavior' => 'default_incomplete',
            'expand' => ['latest_invoice.payment_intent'],
        ]);
    }

    public function cancelSubscription(string $subscriptionId): Subscription
    {
        $this->assertConfigured();
        $sub = Subscription::retrieve($subscriptionId);
        return $sub->cancel();
    }

    public function constructWebhookEvent(string $payload, string $signature): \Stripe\Event
    {
        return \Stripe\Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret')
        );
    }
}
