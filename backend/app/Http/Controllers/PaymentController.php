<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Payment::class);
        $payments = Payment::all();
        return response()->json($payments);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Payment::class);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'order_id' => $request->order_id,
                ],
            ]);

            // Store payment details
            Payment::create([
                'order_id' => $request->order_id,
                'payment_intent_id' => $paymentIntent->id,
                'payment_type' => 'stripe',
                'payment_status' => 'pending',
                'amount' => $request->amount,
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $intent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($intent->status == 'succeeded') {
                $order = Order::findOrFail($request->order_id);
                $order->status = 'Paid';
                $order->payment_intent_id = $intent->id;
                $order->save();

                $payment = Payment::where('payment_intent_id', $intent->id)->first();
                $payment->update([
                    'payment_status' => 'succeeded',
                ]);

                return response()->json(['message' => 'Payment successful.']);
            }

            return response()->json(['message' => 'Payment failed.'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment failed: ' . $e->getMessage()], 500);
        }
    }

    public function show(Payment $payment): JsonResponse
    {
        $this->authorize('view', $payment);
        return response()->json($payment);
    }

    public function update(Request $request, Payment $payment): JsonResponse
    {
        $this->authorize('update', $payment);

        $request->validate([
            'payment_status' => 'required|string|max:255',
        ]);

        $payment->update($request->only('payment_status'));

        return response()->json($payment);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $this->authorize('delete', $payment);
        $payment->delete();

        return response()->json(null, 204);
    }
}
