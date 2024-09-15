<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    protected $stripe;

    public function __construct(StripeClient $stripe)
    {
        $this->middleware('auth:api');
        $this->stripe = $stripe;
    }

    public function index(): JsonResponse
    {
        $user = auth()->user();

        // Only admins can view all payments
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payments = Payment::all();
        return response()->json($payments);
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Only admins can create payments
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1',
        ]);

        // Retrieve the order to get the seller_id
        $order = Order::find($request->order_id);

        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $request->amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'order_id' => $request->order_id,
                ],
            ]);

            Payment::create([
                'order_id' => $request->order_id,
                'payment_intent_id' => $paymentIntent->id,
                'payment_type' => 'stripe',
                'payment_status' => 'pending',
                'amount' => $request->amount,
                'seller_id' => $order->seller_id, // Include seller_id here
            ]);

            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            \Log::error('Error creating payment: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function show(Payment $payment): JsonResponse
    {
        $user = auth()->user();

        // Only admins can view a payment
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($payment);
    }

    public function update(Request $request, Payment $payment): JsonResponse
    {
        $user = auth()->user();

        // Only admins can update a payment
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'payment_status' => 'required|string|max:255',
        ]);

        $payment->update($request->only('payment_status'));

        return response()->json($payment);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $user = auth()->user();

        // Only admins can delete a payment
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payment->delete();

        return response()->json(null, 204);
    }
}
