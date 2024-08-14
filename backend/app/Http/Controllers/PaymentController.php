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

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
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
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $intent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($intent->status == 'succeeded') {
                $order = Order::findOrFail($request->order_id);
                $order->status = 'Paid';
                $order->save();

                Payment::create([
                    'order_id' => $order->id,
                    'payment_type' => 'stripe',
                    'payment_status' => 'succeeded',
                    'amount' => $intent->amount / 100,
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
            'order_id' => 'required|exists:orders,id',
            'payment_status' => 'required|string|max:255',
        ]);

        $payment->update($request->all());

        return response()->json($payment);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $this->authorize('delete', $payment);
        $payment->delete();

        return response()->json(null, 204);
    }
}
