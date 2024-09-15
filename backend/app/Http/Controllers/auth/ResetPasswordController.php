<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Mail\PasswordChangeConfirmationMail;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();

                Mail::to($user->email)->send(new PasswordChangeConfirmationMail($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully.'])
            : response()->json(['message' => 'This password reset token is invalid.'], 400);
    }
}
