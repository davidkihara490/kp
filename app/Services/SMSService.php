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

    public function sendTransportPartnerAssignmentSMS(string $phoneNumber, string $name)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$name}! You have been assinged as a parcel for tramsporting. Log into your portal and assign a driver"
        );
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

    public function sendSenderParcelCreatedSMS(string $phoneNumber, string $senderName, string $parcelId, string $destinationTown)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$senderName}! Your parcel has been booked successfully. You will be notified when the parcel arrives in {$destinationTown}. You can also track on karibuparcels.com with code {$parcelId}."
        );
    }

    public function sendRecipientParcelCreatedSMS(string $phoneNumber, string $recipientName, string $parcelId, string $destinationTown)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$recipientName}! Your parcel has been booked successfully. You will be notified when the parcel arrives in {$destinationTown}. You can also track on karibuparcels.com with code {$parcelId}."
        );
    }

    public function sendTransportParnerParcelBookedSMS(string $phoneNumber, string $origintown, string $destinationtown)
    {
        return $this->sendMessage(
            $phoneNumber,
            "There is a parcel that has been booked from {$origintown} to {$destinationtown}. Login to your portal to see details and assign the driver"
        );
    }

    public function sendAdminSMSAfterParcelIsBooked(string $phoneNumber, string $origintown, string $destinationtown)
    {
        return $this->sendMessage(
            $phoneNumber,
            "There is a parcel that has been booked from {$origintown} to {$destinationtown}. Login to admin portal to see details and assign the transport partner"
        );
    }

    public function sendDriverAssignmentSMS(string $phoneNumber, string $driverName, string $parcelId, string $originTown,  string $destinationTown, string $code)
    {
        return $this->sendMessage(
            $phoneNumber,
            "Hi {$driverName}! A parcel no:{$parcelId} been assigned to you from {$originTown} to {$destinationTown}.Parcel code is {$code}."
        );
    }

    public function sendRecipientSMSWhenParcelArrives(string $phoneNumber, string $recipientName, string $parcelId, string $parcelCode, string $destinationTown)
    {
        return $this->sendMessage(
            $phoneNumber,

            "Hi {$recipientName}! Your parcel with no:{$parcelId} has arrived at {$destinationTown}. Please come with your original ID/passport to collect it. Parcel code:{$parcelCode}. If you send someone, ask them to come with their ID/Passport"
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
