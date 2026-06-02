<?php

namespace App\Http\Controllers\api\v1\Mpesa;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Log;
use App\Mail\NewParcel;
use App\Services\SMSService;
use Illuminate\Support\Facades\Mail;



class MpesaCallbackController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Handle M-Pesa STK Push callback
     */
    public function stkCallback(Request $request, SMSService $smsService)
    {
        // Get the callback data from request and decode it
        $callbackData = $request->json()->all();

        Log::info("+++++++++++++++++++++++++++++++++++++");
        Log::info('M-Pesa STK Callback Received', $callbackData);
        Log::info("+++++++++++++++++++++++++++++++++++++");

        // Check if stkCallback exists
        if (
            !isset($callbackData['Body']) ||
            !isset($callbackData['Body']['stkCallback'])
        ) {
            Log::error('Invalid callback structure', ['payload' => $callbackData]);
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Invalid callback data'
            ]);
        }

        try {

            Log::info('Processing M-Pesa STK Callback', ['MerchantRequestID' => $callbackData['Body']['stkCallback']['MerchantRequestID']]);
            // Process the callback
            $response = $this->mpesaService->handleCallback($callbackData);

            if ($response['success']) {

                $payment = $response['payment'];

                $parcel = $payment->parcel;
                //Sending email to admins
                try {
                    Log::info('Created Parcel. Sending notification to admin');

                    $admins = User::permission([
                        'parcel.view',
                        'parcel.update',
                        'parcel.delete'
                    ])->get();
                    foreach ($admins as $admin) {
                        Mail::to($admin->email)->send(new NewParcel($parcel));
                    }
                } catch (\Throwable $th) {
                    Log::error('Failed to send email to admins after parcel is created: ', [
                        'error' => $th->getMessage(),
                        'stack' => $th->getTraceAsString(),
                    ]);
                }

                //Send Admin SMS notification
                try {
                    Log::info('START::Sending SMS to admin after payment');
                    $smsService->sendAdminSMSAfterParcelIsBooked(
                        formatKenyaNumber('254729005789'),
                        $parcel->senderTown->name,
                        $parcel->receiverTown->name,
                    );
                    Log::info('START::Sending SMS to admin after payment');
                } catch (\Throwable $th) {
                    Log::error('Failed to send SMS to admin after payment: ', [
                        'error' => $th->getMessage(),
                        'stack' => $th->getTraceAsString(),
                    ]);
                }

                //Send SMS to sender
                try {
                    Log::info('Sending SMS to Parcel Sender Start');
                    $smsService->sendSenderParcelCreatedSMS(
                        formatKenyaNumber($parcel->sender_phone),
                        $parcel->sender_name,
                        $parcel->parcel_id,
                        $parcel->receiverTown->name
                    );
                    Log::info('Sending SMS to Parcel Sender End');
                } catch (\Throwable $th) {
                    Log::error('Failed to send SMS to parcel sender: ', [
                        'error' => $th->getMessage(),
                        'stack' => $th->getTraceAsString(),
                    ]);
                }

                //Send SMS to recipients
                try {
                    Log::info('Sending SMS to Parcel Recipient Start');
                    $smsService->sendRecipientParcelCreatedSMS(
                        formatKenyaNumber($parcel->receiver_phone),
                        $parcel->receiver_name,
                        $parcel->parcel_id,
                        $parcel->receiverTown->name
                    );
                    Log::info('Sending SMS to Parcel Recipient End');
                } catch (\Throwable $th) {
                    Log::error('Failed to send SMS to receipient: ', [
                        'error' => $th->getMessage(),
                        'stack' => $th->getTraceAsString(),
                    ]);
                }

                //Send SMS to transport partners
                // try {
                //     $transportPartners = Partner::where('partner_type', 'transport')->where('verification_status', 'verified')->with('owner')->get();
                //     Log::info('Sending SMS to Transport Partner Start');

                //     foreach ($transportPartners as $partner) {

                //         // Send to owner phone
                //         if (!empty($partner->owner?->phone_number)) {
                //             $smsService->sendTransportParnerParcelBookedSMS(
                //                 formatKenyaNumber($partner->owner?->phone_number),
                //                 $parcel->senderTown->name,
                //                 $parcel->receiverTown->name
                //             );
                //         }
                //     }

                //     Log::info('Sending SMS to Transport Partner End');
                // } catch (\Throwable $th) {
                //     Log::error('Failed to send SMS to transport partners: ', [
                //         'error' => $th->getMessage(),
                //         'stack' => $th->getTraceAsString(),
                //     ]);
                // }

                Log::info('Callback processed successfully');

                Log::info("Payment is successful");
                // Return success response to M-Pesa
                return response()->json([
                    'ResultCode' => 0,
                    'ResultDesc' => 'Success'
                ]);
            } else {
                Log::error('Failed to process callback');

                return response()->json([
                    'ResultCode' => 1,
                    'ResultDesc' => 'Failed to process'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Callback processing exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $callbackData
            ]);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Internal server error'
            ]);
        }
    }

    /**
     * Handle C2B (PayBill/Till) callback
     */
    public function c2bCallback(Request $request)
    {
        Log::info('M-Pesa C2B Callback Received', $request->all());

        // Process C2B payment confirmation
        // Similar to STK but different data structure

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ]);
    }

    /**
     * Handle B2C (Business to Customer) callback
     */
    public function b2cCallback(Request $request)
    {
        Log::info('M-Pesa B2C Callback Received', $request->all());

        // Process B2C payment confirmation

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ]);
    }

    /**
     * Test callback endpoint (for development)
     */
    public function testCallback(Request $request)
    {
        // Sample callback data for testing
        $sampleCallbacks = [
            'success' => [
                'Body' => [
                    'stkCallback' => [
                        'MerchantRequestID' => 'AG_20240130_123456789012',
                        'CheckoutRequestID' => 'ws_CO_300120241234567890',
                        'ResultCode' => 0,
                        'ResultDesc' => 'The service request is processed successfully.',
                        'CallbackMetadata' => [
                            'Item' => [
                                ['Name' => 'Amount', 'Value' => 500],
                                ['Name' => 'MpesaReceiptNumber', 'Value' => 'NCB123456789'],
                                ['Name' => 'Balance', 'Value' => 15000],
                                ['Name' => 'TransactionDate', 'Value' => '20240130123456'],
                                ['Name' => 'PhoneNumber', 'Value' => '254712345678'],
                            ]
                        ]
                    ]
                ]
            ],
            'cancelled' => [
                'Body' => [
                    'stkCallback' => [
                        'MerchantRequestID' => 'AG_20240130_987654321098',
                        'CheckoutRequestID' => 'ws_CO_300120249876543210',
                        'ResultCode' => 1032,
                        'ResultDesc' => 'Request cancelled by user.',
                    ]
                ]
            ],
            'failed' => [
                'Body' => [
                    'stkCallback' => [
                        'MerchantRequestID' => 'AG_20240130_555555555555',
                        'CheckoutRequestID' => 'ws_CO_300120245555555555',
                        'ResultCode' => 1,
                        'ResultDesc' => 'The balance is insufficient for the transaction',
                    ]
                ]
            ]
        ];

        $type = $request->input('type', 'success');
        $sampleData = $sampleCallbacks[$type] ?? $sampleCallbacks['success'];

        // Process the sample callback
        $success = $this->mpesaService->handleCallback($sampleData);

        return response()->json([
            'success' => $success,
            'message' => 'Test callback processed',
            'type' => $type,
            'data' => $sampleData,
        ]);
    }
}

// {
//   "Body": {
//     "stkCallback": {
//       "MerchantRequestID": "29115-34620561-1",
//       "CheckoutRequestID": "ws_CO_300120241234567890",
//       "ResultCode": 0,
//       "ResultDesc": "The service request is processed successfully.",
//       "CallbackMetadata": {
//         "Item": [
//           {
//             "Name": "Amount",
//             "Value": 1
//           },
//           {
//             "Name": "MpesaReceiptNumber",
//             "Value": "NCB123456789"
//           },
//           {
//             "Name": "Balance"
//           },
//           {
//             "Name": "TransactionDate",
//             "Value": 20240130123456
//           },
//           {
//             "Name": "PhoneNumber",
//             "Value": 254712345678
//           }
//         ]
//       }
//     }
//   }
// }

// {
//   "Body": {
//     "stkCallback": {
//       "MerchantRequestID": "29115-34620561-1",
//       "CheckoutRequestID": "ws_CO_300120241234567890",
//       "ResultCode": 1032,
//       "ResultDesc": "Request cancelled by user."
//     }
//   }
// }


// {
//   "Body": {
//     "stkCallback": {
//       "MerchantRequestID": "29115-34620561-1",
//       "CheckoutRequestID": "ws_CO_300120241234567890",
//       "ResultCode": 1,
//       "ResultDesc": "The balance is insufficient for the transaction."
//     }
//   }
// }