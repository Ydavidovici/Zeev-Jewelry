<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delivery Confirmation</title>
</head>
<body>
<h1>Delivery Confirmation - Order #{{ $order->id }}</h1>
<p>Hi {{ $order->user->name }},</p>
<p>Your order has been delivered! Here are the details:</p>
<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->product_name }} - {{ $item->quantity }} x ${{ $item->price }}</li>
    @endforeach
</ul>
<p>Total: ${{ $order->total }}</p>
</body>
</html>
