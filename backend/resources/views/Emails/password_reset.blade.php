<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>
<h1>Password Reset Request</h1>
<p>Hi {{ $user->name }},</p>
<p>You requested a password reset. Click the link below to reset your password:</p>
<a href="{{ url('password/reset', $token) }}">Reset Password</a>
</body>
</html>
