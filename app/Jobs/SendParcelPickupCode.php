<?php

namespace App\Jobs;

use App\Models\Parcel;
use App\Services\SMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SendParcelPickupCode implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 5;
    public array $backoff = [30, 120, 300, 600];

    public function __construct(private int $parcelId, private string $code) {}

    public function handle(SMSService $sms): void
    {
        $parcel = Parcel::with('receiverTown')->find($this->parcelId);
        if (!$parcel || $parcel->current_status !== Parcel::STATUS_ARRIVED_AT_DESTINATION
            || !$parcel->otp || !$parcel->otp_expires_at || Carbon::parse($parcel->otp_expires_at)->isPast()
            || !Hash::check($this->code, $parcel->otp)) {
            return;
        }
        $sms->sendRecipientSMSWhenParcelArrives(formatKenyaNumber($parcel->receiver_phone),
            $parcel->receiver_name, $parcel->parcel_id, $this->code, $parcel->receiverTown->name);
    }
}
