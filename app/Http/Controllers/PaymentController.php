<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Payment::class);
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $this->authorize('create', Payment::class);
        return view('payments.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Payment::class);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100, // Amount in cents
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function confirm(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status == 'succeeded') {
            Payment::create([
                'order_id' => $request->order_id,
                'payment_type' => 'stripe',
                'payment_status' => 'succeeded',
                'amount' => $intent->amount / 100,
            ]);

            return redirect()->route('payments.index')->with('success', 'Payment successful.');
        }

        return redirect()->route('payments.index')->with('error', 'Payment failed.');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_status' => 'required|string|max:255',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index');
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        $payment->delete();

        return redirect()->route('payments.index');
    }
}
