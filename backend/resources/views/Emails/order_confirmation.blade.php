<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>
<body>
<h1>Order Confirmation - Order #{{ $order->id }}</h1>
<p>Thank you for your purchase, {{ optional($order->customer)->name ?? 'Valued Customer' }}!</p>
<p>Your order details:</p>

@if ($order->items && count($order->items) > 0)
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item->product_name }} - {{ $item->quantity }} x ${{ $item->price }}</li>
        @endforeach
    </ul>
@else
    <p>No items found for this order.</p>
@endif

<p>Total: ${{ $order->total_amount }}</p>
</body>
</html>
