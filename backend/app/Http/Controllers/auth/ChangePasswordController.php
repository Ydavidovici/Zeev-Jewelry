<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordChangeConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Your current password does not match our records.'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        // Send password change confirmation email
        Mail::to($user->email)->send(new PasswordChangeConfirmationMail($user));

        return response()->json(['message' => 'Password changed successfully.']);
    }
}
