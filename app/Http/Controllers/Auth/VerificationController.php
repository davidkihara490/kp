<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verifyEmail($id, $token)
    {
        $partner = User::findOrFail($id);

        $pPartner = match ($partner->user_type) {
            'pha' => $partner->parcelHandlingAssistant->partner ?? null,
            'driver' => $partner->driver->partner ?? null,
            default => $partner->partner ?? $partner,
        };

        if (
            $partner->email_verification_token !== $token ||
            $partner->email_verification_expires_at < now()
        ) {
            return redirect()->route('partners.account-status', ['id' => $pPartner->id])
                ->with('error', 'Invalid or expired verification link.');
        }

        // Verify the email
        $partner->email_verified_at = now();
        $partner->email_verification_token = null;
        $partner->email_verification_expires_at = null;
        $partner->save();

        // if($partner->email_verified_at && $partner->phone_verified_at && $pPartner->verification_status === 'verified'){
        //         return redirect()->route('partner.dashboard', ['id' => $pPartner->id])
        //             ->with('success', 'Account verified successfully!');
        // }

        // Redirect to dashboard or login with success message
        return redirect()->route('partners.account-status', ['id' => $pPartner->id])
            ->with('success', 'Email verified successfully!');
    }
}
