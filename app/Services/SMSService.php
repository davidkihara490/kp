<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected string $balanceApiUrl;
    protected string $apiUrl;
    protected string $apiKey;
    protected string $senderName;
    protected string $accessKey;
    protected string $clientId;
    protected string $adminPhoneNumber;

    public function __construct()
    {
        $this->apiUrl = config('services.onfonmedia.api_url');
        $this->apiKey = config('services.onfonmedia.api_key');
        $this->senderName = config('services.onfonmedia.sender_name');
        $this->accessKey = config('services.onfonmedia.access_key');
        $this->balanceApiUrl = config('services.onfonmedia.balance_api_url');
        $this->clientId = config('services.onfonmedia.client_id');
        $this->adminPhoneNumber = config('services.onfonmedia.admin_phone');
    }

    public function checkBalance(): float
    {
        $url = $this->balanceApiUrl;

        $queryParams = [
            'ApiKey' => $this->apiKey,
            'ClientId' => $this->clientId,
        ];

        $response = Http::withHeaders([
            'AccessKey' => $this->accessKey,
            'Content-Type' => 'application/json',
        ])->get($url, $queryParams);

        if ($response->successful()) {
            $data = $response->json();
            return isset($data['Data'][0]['Credits'])
                ? floatval($data['Data'][0]['Credits'])
                : 0;
        }

        return 0;
    }

    public function sendMessage(string $phoneNumber, string $message)
    {
        $balance = $this->checkBalance();

        if ($balance < 10) {
            $this->sendAdminAlert("SMS balance is low ({$balance}). Please top up.");
        }

        return $this->sendBulkSMS([
            [
                'phone' => $phoneNumber,
                'message' => $message,
            ],
        ]);
    }

    protected function sendAdminAlert(string $alertMessage)
    {
        $adminPhoneNumber = $this->adminPhoneNumber; // set in .env
        $this->sendBulkSMS(
            [
                [
                    'phone' => $adminPhoneNumber,
                    'message' => $alertMessage,
                ],
            ]
        );
    }

    public function sendBulkSMS(array $recipients)
    {
        $payload = [
            "SenderId" => $this->senderName,
            "MessageParameters" => array_map(function ($item) {
                return [
                    "Number" => $item['phone'],
                    "Text" => $item['message'],
                ];
            }, $recipients),
            "ApiKey" => $this->apiKey,
            "ClientId" => $this->clientId,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'AccessKey' => $this->accessKey,
        ])->post($this->apiUrl, $payload);

        if ($response->successful()) {
            return $response->json();
        }

        Log::info("ERROR:", $response->json());
        return null;
    }

    public function sendDriverWelcomeSMS(string $phoneNumber, string $driverName)
    {
        return $this->sendBulkSMS([
            [
                'phone' => $phoneNumber,
                'message' => "Hi {$driverName}! You have been successfully registered as a driver at Karibu Parcels. Check the link on your email for further instructions.",
            ],
        ]);
    }

    public function sendDriverAssignmentSMS(string $phoneNumber, string $driverName, string $code)
    {
        return $this->sendBulkSMS([
            [
                'phone' => $phoneNumber,
                'message' => "Hi {$driverName}! You have been assigned to deliver a parcel. Parcel code is {$code}. Use this code at the pick up point",
            ],
        ]);
    }

    // /* ================== PUBLIC METHODS ================== */

    // public function notifyRecipientParcelDropOff(string $phoneNumber)
    // {
    //     return $this->send(
    //         $phoneNumber,
    //         'KARIBU PARCELS: We have received your parcel. We will notify you when to pick it.'
    //     );
    // }


    // public function notifySenderParcelReceived(string $phoneNumber)
    // {
    //     return $this->send(
    //         $phoneNumber,
    //         'KARIBU PARCELS: Your parcel has been received and is ready for shipping.'
    //     );
    // }

    // public function notifyCompanyNewParcel(string $phoneNumber)
    // {
    //     return $this->send(
    //         $phoneNumber,
    //         'KARIBU PARCELS: Dear Admin, a new parcel has been processed and is waiting to be shipped.'
    //     );
    // }

    // public function notifySenderOnShipping(string $phoneNumber)
    // {
    //     return $this->send(
    //         $phoneNumber,
    //         'KARIBU PARCELS: Your parcel has been shipped to your selected location.'
    //     );
    // }

    // public function notifyRecipientParcelArrived(string $phoneNumber, string $parcelId)
    // {
    //     return $this->send(
    //         $phoneNumber,
    //         "KARIBU PARCELS: Your parcel with ID {$parcelId} has arrived and is ready for picking."
    //     );
    // }

    // public function notifyAgentOnShipping(string $phoneNumber)
    // {
    //     return $this->send(
    //         $phoneNumber,
    //         'KARIBU PARCELS: A parcel is being shipped to your pick-up location. Please wait to pick it up.'
    //     );
    // }
}
