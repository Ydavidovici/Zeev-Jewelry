<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function handle(Request $request): JsonResponse
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $event = null;

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;

                Log::info('PaymentIntent was successful!');

                $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
                if ($order) {
                    $order->status = 'Paid';
                    $order->save();

                    $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();
                    if ($payment) {
                        $payment->update([
                            'payment_status' => 'succeeded',
                        ]);
                    }
                }
                break;

            default:
                Log::warning('Received unknown event type ' . $event->type);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
