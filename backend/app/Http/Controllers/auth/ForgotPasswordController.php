<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        \Log::channel('custom')->info('Reset password initiated for email: ' . $request->email);

        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            \Log::channel('custom')->info('User found: ' . $user->email);
            $token = Password::createToken($user);

            // Send password reset email
            Mail::to($user->email)->send(new PasswordResetMail($user, $token));
            \Log::channel('custom')->info('Password reset email sent to: ' . $user->email);
        } else {
            \Log::channel('custom')->info('No user found with email: ' . $request->email);
        }

        return response()->json(['message' => 'If the email address is registered, you will receive a reset link.']);
    }
}
