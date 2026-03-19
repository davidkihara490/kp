<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMSService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $senderName;
    protected $accessKey;

    public function __construct()
    {
        // $this->apiUrl = 'https://app.mobitechtechnologies.com/sms/sendsms';
        // $this->apiKey = config('services.mobitech.api_key');
        // $this->senderName = config('services.mobitech.sender_name', '23107');


        $this->apiUrl = config('services.onfonmedia.api_url');
        $this->apiKey = config('services.onfonmedia.api_key');
        $this->senderName = config('services.onfonmedia.sender_name');
        $this->accessKey = config('services.onfonmedia.access_key');
    }

    // protected function send(string $phoneNumber, string $message)
    // {
    //     $response = Http::withHeaders([
    //         'Accesskey' => $this->accessKey,
    //         'Content-Type' => 'application/json',
    //     ])->post($this->apiUrl, [
    //         "SenderId" => $this->senderName,
    //         "MessageParameters" => [
    //             [
    //                 "Number" => $phoneNumber,
    //                 "Text" => $message
    //             ]
    //         ],
    //         "ApiKey" => $this->apiKey,
    //         "ClientId" => $this->senderName
    //     ]);

    //     if ($response->successful()) {
    //         return $response->json();
    //     }

    //     return $response->body();
    // }


    public function send()
    {
        $response = Http::withHeaders([
            'AccessKey' => config('services.onfonmedia.access_key'),
            'Content-Type' => 'application/json',
        ])->post("https://api.onfonmedia.co.ke/v1/sms/SendBulkSMS", [
            "SenderId" => config('services.onfonmedia.sender_name'),
            "MessageParameters" => [
                [
                    "Number" => "254706506361",
                    "Text" => "Test Message"
                ],
            ],
            "ApiKey" => config('services.onfonmedia.api_key'),
            "ClientId" => config('services.onfonmedia.client_id'),
        ]);

        return $response->json();
    }


    // protected function send(string $phoneNumber, string $message): ?string
    // {
    //     $payload = [
    //         'mobile' => $phoneNumber,
    //         'response_type' => 'json',
    //         'sender_name' => $this->senderName,
    //         'service_id' => 0,
    //         'message' => $message,
    //     ];

    //     $curl = curl_init();

    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => $this->apiUrl,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_TIMEOUT => 15,
    //         CURLOPT_POST => true,
    //         CURLOPT_POSTFIELDS => json_encode($payload),
    //         CURLOPT_HTTPHEADER => [
    //             'h_api_key: ' . $this->apiKey,
    //             'Content-Type: application/json',
    //         ],
    //     ]);

    //     $response = curl_exec($curl);
    //     curl_close($curl);

    //     return $response;
    // }

    //     protected function send(){
    //         curl --location 'https://api.onfonmedia.com/v1/sms/SendBulkSMS' \
    // --header 'Accesskey: Valuehere' \
    // --header 'Content-Type: application/json' \
    // --data '{
    //   "SenderId": "senderid value",
    //   "MessageParameters": [

    //         {
    //       "Number":"msisdn here", 
    //       "Text": "content"
    //     }
    //   ],
    //   "ApiKey":"apikey value here",
    //   "ClientId": "client id value here"
    // }'
    //     }



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
