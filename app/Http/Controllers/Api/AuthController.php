<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        // Generate Token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }
    public function register(Request $request){
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
        ]);

        // ✅ Create user
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // ✅ Generate token (لو بتستخدم Sanctum أو Passport)
        $token = $user->createToken('api_token')->plainTextToken;

        // ✅ Return JSON response
        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }
    public function sendResetLink(Request $request)
    {
        // ✅ Validate email
        $request->validate([
            'email' => 'required|email|exists:users,email|max:255',
        ]);

        // ✅ Attempt to send reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // ✅ Check result
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'A reset link has been sent to your email address.',
                'status'  => true,
            ], 200);
        }

        return response()->json([
            'message' => 'Unable to send reset link. Please try again later.',
            'status'  => false,
        ], 400);
    }
    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // ✅ Reset password using Laravel built-in
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // ✅ Return response
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password reset successfully.',
                'status' => true,
            ], 200);
        }

        return response()->json([
            'message' => 'Something went wrong. Please check the token or email.',
            'status' => false,
        ], 400);
    }

}
