<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret'); // Fetch webhook secret from config

        try {
            // Verify the event by checking the signature
            $event = Webhook::constructEvent($payload, $sig_header, $secret);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Invalid Stripe signature', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'failure', 'message' => 'Invalid signature'], 400);
        }

        // Handle specific event types
        if ($event['type'] == 'payment_intent.succeeded') {
            $paymentIntent = $event['data']['object']; // Stripe payment intent details
            Log::info('Payment succeeded for payment intent: ' . $paymentIntent['id']);

            // Find the payment record in the database and update its status
            $payment = \App\Models\Payment::where('stripe_payment_id', $paymentIntent['id'])->first();

            if ($payment) {
                // Update payment status
                $payment->update([
                    'payment_status' => 'succeeded'
                ]);

                // Update related order status to 'Paid'
                $order = \App\Models\Order::find($payment->order_id);
                $order->update([
                    'status' => 'Paid'
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

}
