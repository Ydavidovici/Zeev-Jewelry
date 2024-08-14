<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <form method="POST" action="{{ route('password.email') }}">
@csrf
<input type="email" name="email" placeholder="Email" required autofocus>
<button type="submit">Send Password Reset Link</button>
</form>
</body>
</html>
