<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    // -------------------------
    // API configuration
    // -------------------------
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

    // -------------------------
    // Public methods
    // -------------------------

    /**
     * Send SMS to a single recipient
     */
    public function sendMessage(string $phoneNumber, string $message)
    {
        // Ensure there is enough balance before sending
        if (!$this->hasSufficientBalance()) {
            $this->sendAdminAlert("SMS balance is low. Please top up.");
            return null;
        }

        return $this->sendBulkSMS([
            ['phone' => $phoneNumber, 'message' => $message]
        ]);
    }

    /**
     * Send welcome SMS to a parcel handling assistant
     */
    public function sendParcelHandlingAssistantWelcomeSMS(string $phoneNumber, string $name)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$name}! You have been registered as a parcel handling assistant at Karibu Parcels. Check your email for further instructions."
        );
    }

    /**
     * Send welcome SMS to a driver
     */
    public function sendDriverWelcomeSMS(string $phoneNumber, string $driverName)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$driverName}! You have been successfully registered as a driver at Karibu Parcels. Check the link on your email for further instructions."
        );
    }

    /**
     * Send driver assignment SMS
     */
    public function sendDriverAssignmentSMS(string $phoneNumber, string $driverName, string $code)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$driverName}! You have been assigned to deliver a parcel. Parcel code is {$code}. Use this code at the pick up point."
        );
    }

    // -------------------------
    // Bulk SMS & admin alerts
    // -------------------------

    /**
     * Send multiple SMS messages in bulk
     */
    public function sendBulkSMS(array $recipients)
    {
        if (!$this->hasSufficientBalance()) {
            return $this->sendAdminAlert("SMS balance is low. Please top up.");
        }

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

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Failed to send SMS", ['payload' => $payload, 'response' => $response->json()]);
        return null;
    }

    /**
     * Notify admin in case of low balance or other alerts
     */
    protected function sendAdminAlert(string $alertMessage)
    {
        return $this->sendBulkSMS([
            ['phone' => $this->adminPhoneNumber, 'message' => $alertMessage]
        ]);
    }

    // -------------------------
    // Balance checking
    // -------------------------

    /**
     * Check current SMS balance
     */
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

    /**
     * Ensure balance is above minimum threshold
     */
    protected function hasSufficientBalance(float $min = 10.0): bool
    {
        return $this->checkBalance() >= $min;
    }

    // -------------------------
    // Helper methods
    // -------------------------

    /**
     * Common HTTP headers for API requests
     */
    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'AccessKey' => $this->accessKey,
        ];
    }
}
