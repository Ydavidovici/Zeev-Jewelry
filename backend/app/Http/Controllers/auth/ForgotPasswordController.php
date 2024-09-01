<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user && Gate::allows('send-reset-link-email', $user)) {
            $token = Password::createToken($user);

            // Send password reset email
            Mail::to($user->email)->send(new PasswordResetMail($user, $token));
        }

        return response()->json(['message' => 'If the email address is registered, you will receive a reset link.']);
    }
}
