<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verifyEmail(Request $request, $id, $hash)
    {
        // Fetch user directly
        $user = User::findOrFail($id);

        // Validate email hash
        if (! hash_equals(sha1($user->email), $hash)) {
            abort(403, 'Invalid verification link.');
        }

        // Manually verify email
        if ($user->email_verified_at === null) {
            $user->email_verified_at = now();
            $user->save();
        }

        return redirect()
            ->route('partners.login')
            ->with('success', 'Email verified successfully.');
    }
}
