<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Review Request</title>
</head>
<body>
<h1>We’d Love to Hear Your Feedback!</h1>
<p>Hi {{ $order->user->name }},</p>
<p>Thank you for your recent purchase! We hope you’re enjoying your new items. Please take a moment to leave a review and let us know what you think.</p>
<a href="{{ url('review', $order->id) }}">Leave a Review</a>
</body>
</html>
