<?php

namespace Tests\Unit;

use App\Services\StripeService;
use Tests\TestCase;

class StripeServiceTest extends TestCase
{
    public function test_constructor_does_not_throw_without_stripe_key(): void
    {
        config(['services.stripe.secret' => '']);

        $service = new StripeService();

        $this->assertInstanceOf(StripeService::class, $service);
    }

    public function test_create_payment_intent_throws_when_not_configured(): void
    {
        config(['services.stripe.secret' => '']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/STRIPE_SECRET/');

        (new StripeService())->createPaymentIntent(1);
    }

    public function test_refund_throws_when_not_configured(): void
    {
        config(['services.stripe.secret' => '']);

        $this->expectException(\RuntimeException::class);

        (new StripeService())->refundPaymentIntent('pi_test_123');
    }

    public function test_create_or_retrieve_customer_throws_when_not_configured(): void
    {
        config(['services.stripe.secret' => '']);

        $this->expectException(\RuntimeException::class);

        (new StripeService())->createOrRetrieveCustomer('test@example.com');
    }

    public function test_create_subscription_throws_when_not_configured(): void
    {
        config(['services.stripe.secret' => '']);

        $this->expectException(\RuntimeException::class);

        (new StripeService())->createSubscription('cus_test', 'price_test');
    }

    public function test_cancel_subscription_throws_when_not_configured(): void
    {
        config(['services.stripe.secret' => '']);

        $this->expectException(\RuntimeException::class);

        (new StripeService())->cancelSubscription('sub_test');
    }
}
