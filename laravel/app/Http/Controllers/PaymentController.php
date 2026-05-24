<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Payment;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private StripeService $stripe) {}

    public function show(Claim $claim): View|\Illuminate\Http\RedirectResponse
    {
        // Subscribers skip payment
        if (auth()->check() && auth()->user()->hasActiveSubscription()) {
            return redirect()->route('claim.download', $claim);
        }

        return view('claim.payment', [
            'claim' => $claim,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    public function createIntent(Request $request, Claim $claim)
    {
        abort_if($claim->isPaid(), 409, 'Esta reclamación ya tiene un pago registrado');

        $intent = $this->stripe->createPaymentIntent($claim->id);

        Payment::create([
            'claim_id' => $claim->id,
            'user_id' => auth()->id(),
            'stripe_payment_intent_id' => $intent->id,
            'amount_cents' => 999,
            'currency' => 'eur',
            'status' => Payment::STATUS_PENDING,
        ]);

        return response()->json(['client_secret' => $intent->client_secret]);
    }

    public function success(Claim $claim): View
    {
        return view('claim.download', compact('claim'));
    }
}
