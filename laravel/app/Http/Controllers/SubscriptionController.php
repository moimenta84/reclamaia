<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(private StripeService $stripe) {}

    public function plans(): View
    {
        $user = auth()->user();
        return view('subscription.plans', [
            'hasSubscription' => $user->hasActiveSubscription(),
            'stripeKey' => config('services.stripe.key'),
            'priceId' => config('services.stripe.subscription_price_id'),
        ]);
    }

    public function subscribe(Request $request)
    {
        $user = auth()->user();

        if ($user->hasActiveSubscription()) {
            return back()->with('info', 'Ya tienes una suscripción activa.');
        }

        // Create or retrieve Stripe customer
        if (!$user->stripe_customer_id) {
            $customer = $this->stripe->createOrRetrieveCustomer($user->email, $user->name);
            $user->update(['stripe_customer_id' => $customer->id]);
        }

        $priceId = config('services.stripe.subscription_price_id');
        $sub = $this->stripe->createSubscription($user->stripe_customer_id, $priceId);

        // Subscription webhook will update the DB once confirmed
        return response()->json([
            'client_secret' => $sub->latest_invoice->payment_intent->client_secret,
            'subscription_id' => $sub->id,
        ]);
    }

    public function cancel(Request $request)
    {
        $user = auth()->user();
        $subscription = $user->subscription;

        if (!$subscription || !$subscription->isActive()) {
            return back()->with('error', 'No tienes una suscripción activa.');
        }

        $this->stripe->cancelSubscription($subscription->stripe_subscription_id);
        $subscription->update(['status' => 'canceled', 'canceled_at' => now()]);
        $user->update(['plan' => 'free']);

        return redirect()->route('dashboard')->with('success', 'Suscripción cancelada. El acceso ilimitado continuará hasta el final del período pagado.');
    }
}
