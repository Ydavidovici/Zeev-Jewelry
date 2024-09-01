<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordChangeConfirmationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $response = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->save();
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    // Send password change confirmation email
                    Mail::to($user->email)->send(new PasswordChangeConfirmationMail($user));
                }
                return response()->json(['message' => 'Password reset successfully.'], 200);
            } else {
                return response()->json(['message' => __($response)], 400);
            }

        } catch (\Exception $e) {
            \Log::error('Password reset failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to reset password.'], 500);
        }
    }
}
