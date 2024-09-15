<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Refund</title>
</head>
<body>
<h1>Order Refund - Order #{{ $order->id }}</h1>
<p>Hi {{ $order->user->name }},</p>
<p>Your refund has been processed. Here are the details:</p>
<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->product_name }} - {{ $item->quantity }} x ${{ $item->price }}</li>
    @endforeach
</ul>
<p>Total Refunded: ${{ $order->total }}</p>
</body>
</html>
