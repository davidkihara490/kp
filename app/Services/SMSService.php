<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected string $apiUrl;
    protected string $balanceApiUrl;
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

    public function sendMessage(string $phoneNumber, string $message)
    {
        if (!$this->hasSufficientBalance()) {
            $this->sendAdminAlert("SMS balance is low. Please top up.");
            return null;
        }

        return $this->sendBulkSMS([
            ['phone' => $phoneNumber, 'message' => $message]
        ]);
    }

    public function sendParcelHandlingAssistantWelcomeSMS(string $phoneNumber, string $name)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$name}! You have been registered as a parcel handling assistant at Karibu Parcels. Check the link on youe email for further instructions."
        );
    }
    public function sendDriverWelcomeSMS(string $phoneNumber, string $driverName)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$driverName}! You have been successfully registered as a driver at Karibu Parcels. Check the link on your email for further instructions."
        );
    }

    public function sendDriverAssignmentSMS(string $phoneNumber, string $driverName, string $code)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$driverName}! You have been assigned to deliver a parcel. Parcel code is {$code}. Use this code at the pick up point."
        );
    }
    public function sendBulkSMS(array $recipients)
    {
        // if (!$this->hasSufficientBalance()) {
        //     return $this->sendAdminAlert("SMS balance is low. Please top up.");
        // }

        $payload = [
            "SenderId" => $this->senderName,
            "MessageParameters" => array_map(fn($item) => [
                "Number" => $item['phone'],
                "Text" => $item['message'],
            ], $recipients),
            "ApiKey" => $this->apiKey,
            "ClientId" => $this->clientId,
        ];

        $response = Http::withHeaders($this->getHeaders())->post($this->apiUrl, $payload);

        Log::info("SMS API Response", ['payload' => $payload, 'response' => $response->json()]);
        if ($response->successful()) {
            return $response->json();
        }

        dd($response->json());

        Log::info("Failed to send SMS", ['payload' => $payload, 'response' => $response->json()]);
        return null;
    }

    protected function sendAdminAlert(string $alertMessage)
    {

        return $this->sendBulkSMS([
            ['phone' => $this->adminPhoneNumber, 'message' => $alertMessage]
        ]);
    }

    public function checkBalance(): float
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->balanceApiUrl, [
                'ApiKey' => $this->apiKey,
                'ClientId' => $this->clientId,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return isset($data['Data'][0]['Credits']) ? floatval($data['Data'][0]['Credits']) : 0;
        }

        Log::error("Failed to check SMS balance", ['response' => $response->json()]);
        return 0;
    }

    protected function hasSufficientBalance(float $min = 10.0): bool
    {
        return $this->checkBalance() >= $min;
    }
    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'AccessKey' => $this->accessKey,
        ];
    }
}
