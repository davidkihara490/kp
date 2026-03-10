<?php

namespace App\Services;

class SMSService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $senderName;

    public function __construct()
    {
        $this->apiUrl = 'https://app.mobitechtechnologies.com/sms/sendsms';
        $this->apiKey = config('services.mobitech.api_key');
        $this->senderName = config('services.mobitech.sender_name', '23107');
    }

    protected function send(string $phoneNumber, string $message): ?string
    {
        $payload = [
            'mobile' => $phoneNumber,
            'response_type' => 'json',
            'sender_name' => $this->senderName,
            'service_id' => 0,
            'message' => $message,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'h_api_key: ' . $this->apiKey,
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function sendDriverWelcomeSMS(string $phoneNumber, string $driverName)
    {
        return $this->send(
            $phoneNumber,
            "Hi {$driverName}! You have been successfully registered as a driver at Karibu Parcels. Check the link on your email for further instructions."
        );
    }

    /* ================== PUBLIC METHODS ================== */

    public function notifyRecipientParcelDropOff(string $phoneNumber)
    {
        return $this->send(
            $phoneNumber,
            'KARIBU PARCELS: We have received your parcel. We will notify you when to pick it.'
        );
    }


    public function notifySenderParcelReceived(string $phoneNumber)
    {
        return $this->send(
            $phoneNumber,
            'KARIBU PARCELS: Your parcel has been received and is ready for shipping.'
        );
    }

    public function notifyCompanyNewParcel(string $phoneNumber)
    {
        return $this->send(
            $phoneNumber,
            'KARIBU PARCELS: Dear Admin, a new parcel has been processed and is waiting to be shipped.'
        );
    }

    public function notifySenderOnShipping(string $phoneNumber)
    {
        return $this->send(
            $phoneNumber,
            'KARIBU PARCELS: Your parcel has been shipped to your selected location.'
        );
    }

    public function notifyRecipientParcelArrived(string $phoneNumber, string $parcelId)
    {
        return $this->send(
            $phoneNumber,
            "KARIBU PARCELS: Your parcel with ID {$parcelId} has arrived and is ready for picking."
        );
    }

    public function notifyAgentOnShipping(string $phoneNumber)
    {
        return $this->send(
            $phoneNumber,
            'KARIBU PARCELS: A parcel is being shipped to your pick-up location. Please wait to pick it up.'
        );
    }
}
