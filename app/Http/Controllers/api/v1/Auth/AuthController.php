<?php

namespace App\Http\Controllers\api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login using email or phone number + password.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login'    => ['required', 'string'], // email OR phone_number
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $login = trim($request->input('login'));

        // Determine whether the login is an email or a phone number
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        $user = User::with('partner')->where($field, $login)->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return $this->validationError([
                'login' => [__('auth.failed')],
            ]);
        }

        if (! $this->isActive($user)) {
            return response()->json([
                'status'  => false,
                'message' => __('Your account is not active. Please contact support.'),
            ], 403);
        }

        $token = $user->createToken(
            $request->input('device_name') ?? 'api-token'
        )->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => __('Login successful.'),
            'data'    => [
                'user'       => new UserResource($user),
                'token'      => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    /**
     * Logout the authenticated user (revoke current token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => __('Logged out successfully.'),
        ], 200);
    }

    /**
     * Logout from all devices (revoke all tokens).
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status'  => true,
            'message' => __('Logged out from all devices successfully.'),
        ], 200);
    }

    /**
     * Get the authenticated user's profile.
     */
    public function me(Request $request): JsonResponse
    {

        $user = User::with('partner')->where('id', $request->user()->id)->first();

        return response()->json([
            'status' => true,
            'data'   => new UserResource($user),
        ], 200);
    }

    /**
     * Refresh the current token (issue a new one and revoke the old one).
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->user()->currentAccessToken()->delete();
        $token = $user->createToken($request->input('device_name', 'api-token'))->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => __('Token refreshed successfully.'),
            'data'    => [
                'token'      => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    /**
     * Send a password reset link to the given email address.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $user = User::where('email', $request->input('email'))->first();

        // Do not reveal whether the email exists (security best practice)
        if (! $user) {
            return response()->json([
                'status'  => true,
                'message' => __('If that email exists, a password reset link has been sent.'),
            ], 200);
        }

        // Create a reset token manually (works without a full broker setup)
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email'      => $user->email,
                'token'      => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // TODO: Send email here.
        // Mail::to($user->email)->send(new ResetPasswordMail($user, $token));
        // Or use Laravel's broker:
        // $status = Password::sendResetLink($request->only('email'));

        return response()->json([
            'status'  => true,
            'message' => __('If that email exists, a password reset link has been sent.'),
        ], 200);
    }

    /**
     * Reset the password using the token from the reset email.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'                 => ['required', 'email'],
            'token'                 => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->input('email'))
            ->first();

        if (! $record) {
            return response()->json([
                'status'  => false,
                'message' => __('Invalid or expired reset token.'),
            ], 422);
        }

        // Check token expiry (e.g. 60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $record->email)->delete();

            return response()->json([
                'status'  => false,
                'message' => __('Reset token has expired.'),
            ], 422);
        }

        if (! Hash::check($request->input('token'), $record->token)) {
            return response()->json([
                'status'  => false,
                'message' => __('Invalid reset token.'),
            ], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => __('User not found.'),
            ], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Revoke all existing tokens for security
        $user->tokens()->delete();

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return response()->json([
            'status'  => true,
            'message' => __('Password has been reset successfully.'),
        ], 200);
    }

    /**
     * Change password for the authenticated user.
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $user = $request->user();

        if (! Hash::check($request->input('current_password'), $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => [__('The current password is incorrect.')],
            ]);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Revoke other tokens (keep current one)
        $user->tokens()
            ->where('id', '!=', $request->user()->currentAccessToken()->id)
            ->delete();

        return response()->json([
            'status'  => true,
            'message' => __('Password changed successfully.'),
        ], 200);
    }

    /**
     * Check if a given user account is active.
     */
    protected function isActive(User $user): bool
    {
        // Adjust to your actual status values (e.g. 1, 'active', 'approved', etc.)
        return (int) $user->status === 1 || $user->status === 'active';
    }

    /**
     * Consistent 422 response for validation errors.
     */
    protected function validationError($errors): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => __('Validation failed.'),
            'errors'  => $errors,
        ], 422);
    }
}
