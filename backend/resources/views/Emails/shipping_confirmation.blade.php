<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Confirmation - Order #{{ $order->id }}</title>
</head>
<body>
<h1>Shipping Confirmation - Order #{{ $order->id }}</h1>

@if($order->user && $order->user->name)
    <p>Hi {{ $order->user->name }},</p>
@else
    <p>Hi Valued Customer,</p>
@endif

<p>Your order has been shipped! Here are the details:</p>

@if($order->items && count($order->items) > 0)
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item->product_name ?? 'Unknown Product' }} - {{ $item->quantity ?? 'N/A' }} x ${{ $item->price ?? 'N/A' }}</li>
        @endforeach
    </ul>
@else
    <p>No items found in your order.</p>
@endif

@if($order->tracking_number)
    <p>Tracking Number: {{ $order->tracking_number }}</p>
@else
    <p>No tracking number available at the moment.</p>
@endif

<p>Total: ${{ $order->total ?? 'N/A' }}</p>
</body>
</html>
